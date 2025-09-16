# facturia.py
import json
from typing import Dict, Any
from datetime import datetime
from pathlib import Path

import fitz  # PyMuPDF
from fastapi import FastAPI, HTTPException
from fastapi.routing import APIRoute
from gpt4all import GPT4All

app = FastAPI()

# ⚠️ Ruta típica en Laravel
BASE_FACTURAS = Path(r"C:\Users\User\Desktop\CODIGO\CODIGO\A-Codigo\IA FACTURA\storage\app\storage\archivos_facturas")
MODELO = "Nous-Hermes-2-Mistral-7B-DPO.Q4_0.gguf"
RUTA_MODELO = r"C:\Users\User\AppData\Local\nomic.ai\GPT4All"

INTRO = "Sos un extractor estricto. Devolvés ÚNICAMENTE JSON válido (sin texto extra, sin ```)."


PROMPT_FACTURA_JSON = """
Extraé de esta factura la información en JSON con la siguiente estructura:
- Estructura EXACTA abajo; todas las claves deben estar presentes. - Si un dato no aparece, usá null. - Para números: usá punto decimal y SIN separador de miles (ej: "1234.56"); si el texto usa formato europeo (puntos miles y coma decimal), convertílo. - Fechas en "YYYY-MM-DD". - Monedas en ISO 4217 (ARS, EUR, USD) si se infiere; si no, null. - No inventes valores. - En strings, eliminá saltos de línea y espacios duplicados (colapsá a un solo espacio).
{
  "nro_factura": "string|null",
  "fecha_factura": "YYYY-MM-DD|null",
  "nombre_persona": "string|null",
  "moneda": "string|null",
  "totales": {
    "base_imponible": "string|null",
    "subtotal": "string|null",
    "iva": "string|null",
    "total": "string|null"
  },
  "items": [
    {
      "cantidad": "string|null",
      "descripcion": "string|null",
      "precio_unitario": "string|null",
      "importe": "string|null",
      "moneda": "string|null"
    }
  ]
}
Respondé solo con el JSON, sin texto adicional ni explicaciones. Asegúrate de que todos los campos estén presentes, aunque algunos puedan estar vacíos. Si no hay información disponible, deja el campo vacío.

Aquí está el texto de la factura:
"""

_model = None
def get_model():
    global _model
    if _model is None:
        _model = GPT4All(MODELO, model_path=RUTA_MODELO, device="cuda")
    return _model

def extraer_texto_pdf(ruta_pdf: Path) -> str:
    with fitz.open(str(ruta_pdf)) as doc:
        return "\n".join([page.get_text("text") for page in doc])

def procesar_factura_json(ruta_pdf: Path):
    texto = extraer_texto_pdf(ruta_pdf)
    model = get_model()
    with model.chat_session() as chat:
        chat.generate(INTRO, max_tokens=1024)
        respuesta = chat.generate(f"{PROMPT_FACTURA_JSON}\n{texto}", max_tokens=2024)
    try:
        return json.loads(respuesta)
    except json.JSONDecodeError:
        return None

@app.get("/health")
def health():
    return {"ok": True}

# ✅ el nombre del parámetro debe coincidir con {lote_nombre}
@app.get("/lotes/{lote_nombre}/facturas")
def procesar_lote(lote_nombre: str, save: bool = False):
    carpeta = BASE_FACTURAS / lote_nombre
    if not carpeta.is_dir():
        raise HTTPException(status_code=404, detail=f"No existe la carpeta: {carpeta}")

    pdfs = [p for p in carpeta.iterdir() if p.is_file() and p.suffix.lower() == ".pdf"]
    resultados: Dict[str, Any] = {}

    for pdf in pdfs:
        r = procesar_factura_json(pdf)
        if r is not None:
            resultados[pdf.name] = r

    out = {
        "ok": True,
        "lote": lote_nombre,
        "carpeta": str(carpeta),
        "total_pdfs": len(pdfs),
        "procesados": len(resultados),
        "resultados": resultados,  # <— Aca viene TODO el JSON útil
    }

    if save:
        ts = datetime.now().strftime("%Y%m%d_%H%M%S")
        outfile = carpeta / f"facturas_estructuradas_{ts}.json"
        with open(outfile, "w", encoding="utf-8") as f:
            json.dump(resultados, f, indent=4, ensure_ascii=False)
        out["saved_at"] = str(outfile)

    return out

# 🔎 para verificar qué rutas están registradas (si ves 404/405):
@app.get("/__routes")
def list_routes():
    return [{"path": r.path, "methods": sorted(list(r.methods))}
            for r in app.routes if isinstance(r, APIRoute)]
