IA FACTURAS — README

> Pipeline simple para **subir facturas**, **extraer datos** con un microservicio **FastAPI + GPT4All**, **guardar** en MySQL y **visualizar** en Laravel con un mini‑dashboard por **lote**.

---

## Índice

- [Arquitectura](#arquitectura)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Configuración (.env)](#configuración-env)
- [Estructura de carpetas](#estructura-de-carpetas)
- [Base de datos](#base-de-datos)
- [Modelos](#modelos)
- [Flujo de uso](#flujo-de-uso)
- [Endpoints y rutas](#endpoints-y-rutas)
- [Dashboard por lote](#dashboard-por-lote)
- [Normalización de números y fechas](#normalización-de-números-y-fechas)
- [Rendimiento y timeouts](#rendimiento-y-timeouts)
- [Troubleshooting](#troubleshooting)
- [Roadmap / Ideas](#roadmap--ideas)
- [Licencia](#licencia)

---

## Arquitectura

- **Laravel** (app principal)
  - Sube archivos PDF a una carpeta por **lote** (carpeta).
  - Guarda cada archivo en tabla `archivos` (con `user_id=1` para “Generales”).  
  - Vista para **procesar** un lote (llama a FastAPI), **importar** resultados y **listar** facturas con **dashboard**.

- **FastAPI** (microservicio)
  - Lee PDFs de `storage/app/.../archivos_facturas/<lote>`.
  - Extrae texto con **PyMuPDF** (`fitz`) y estructura con **GPT4All** (modelo local).
  - Devuelve **JSON normalizado** (opcional: guardado `.json`).
  - Soporta **tandas** (`limit`, `offset`) para lotes grandes.

- **MySQL**
  - `factura_extracciones`: metadatos de cada PDF (nro, fecha, totales, persona, json crudo).
  - `factura_items`: items por factura (cantidad, descripción, importe, moneda).

---

## Requisitos

- **PHP** 8.0+ (recomendado 8.1+) y **Composer**
- **Laravel** 11
- **MySQL/MariaDB**
- **Python** 3.10+
  - `fastapi`, `uvicorn`, `pymupdf`, `gpt4all`
- **Modelo GPT4All** (ej.: `Nous-Hermes-2-Mistral-7B-DPO.Q4_0.gguf`)
  - Path típico Windows: `C:\Users\<usuario>\AppData\Local\nomic.ai\GPT4All`

---

## Instalación

### 1) Laravel

```bash
composer install
cp .env.example .env
php artisan key:generate
# configurar conexión a BD en .env
php artisan migrate
php artisan storage:link
2) FastAPI
bash
Mostrar siempre los detalles

Copiar código
pip install fastapi uvicorn pymupdf gpt4all
uvicorn server:app --reload --port 8001
Ajustá rutas/modelo en server.py (ver Configuración).

Configuración (.env)
Laravel

env
Mostrar siempre los detalles

Copiar código
# URL del microservicio
FACTURAS_API_URL=http://127.0.0.1:8001
FACTURAS_API_KEY=            # opcional

# Ruta base de facturas (como las trabajás en tu proyecto)
# ejemplo típico Laravel (disk public):
# ARCHIVOS_FACTURAS=public/archivos_facturas
FastAPI (server.py)

python
Mostrar siempre los detalles

Copiar código
BASE_FACTURAS = Path(r"C:\...\IA FACTURA\storage\app\public\archivos_facturas")
MODELO = "Nous-Hermes-2-Mistral-7B-DPO.Q4_0.gguf"
RUTA_MODELO = r"C:\Users\User\AppData\Local\nomic.ai\GPT4All"
Nota: Si en tu proyecto usás storage\\app\\storage\\archivos_facturas, mantené la misma ruta en ambas apps.

Estructura de carpetas
cpp
Mostrar siempre los detalles

Copiar código
storage/
  app/
    public/
      archivos_facturas/
        LOTE_2025_09/
          factura1.pdf
          factura2.pdf
        LOTE_PRUEBA/
          ...
Cada lote es una subcarpeta dentro de archivos_facturas/.

Base de datos
Tablas nuevas (MySQL):

sql
Mostrar siempre los detalles

Copiar código
CREATE TABLE IF NOT EXISTS factura_extracciones (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  archivo_id BIGINT UNSIGNED NOT NULL,
  raw_json JSON NOT NULL,
  moneda VARCHAR(10) NULL,
  base_imponible DECIMAL(18,2) NULL,
  iva DECIMAL(18,2) NULL,
  total DECIMAL(18,2) NULL,
  subtotal DECIMAL(18,2) NULL,
  fecha_factura DATE NULL,
  nro_factura VARCHAR(60) NULL,
  nombre_persona VARCHAR(200) NULL,
  extracted_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE KEY factura_extracciones_archivo_id_unique (archivo_id),
  KEY idx_fact_extr_nro_factura (nro_factura),
  KEY idx_fact_extr_nombre_persona (nombre_persona),
  CONSTRAINT fk_fact_extr_archivo
    FOREIGN KEY (archivo_id) REFERENCES archivos(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS factura_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  factura_extraccion_id BIGINT UNSIGNED NOT NULL,
  descripcion VARCHAR(500) NOT NULL,
  cantidad DECIMAL(18,4) NULL,
  precio_unitario DECIMAL(18,2) NULL,
  importe DECIMAL(18,2) NULL,
  moneda VARCHAR(10) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  KEY idx_fact_items_factura (factura_extraccion_id),
  KEY idx_fact_items_moneda (moneda),
  CONSTRAINT fk_fact_items_extr
    FOREIGN KEY (factura_extraccion_id) REFERENCES factura_extracciones(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
Si tu motor no soporta JSON, usá LONGTEXT para raw_json.

Modelos
app/Models/FacturaExtraccion.php

php
Mostrar siempre los detalles

Copiar código
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaExtraccion extends Model
{
    protected $table = 'factura_extracciones';
    protected $fillable = [
        'archivo_id','raw_json','moneda','base_imponible','iva','total','subtotal',
        'fecha_factura','nro_factura','nombre_persona','extracted_at',
    ];
    protected $casts = [
        'raw_json'=>'array','fecha_factura'=>'date','extracted_at'=>'datetime',
        'base_imponible'=>'decimal:2','iva'=>'decimal:2','total'=>'decimal:2','subtotal'=>'decimal:2',
    ];
    public function archivo(){ return $this->belongsTo(Archivo::class); }
    public function items(){ return $this->hasMany(FacturaItem::class, 'factura_extraccion_id'); }
}
app/Models/FacturaItem.php

php
Mostrar siempre los detalles

Copiar código
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaItem extends Model
{
    protected $table = 'factura_items';
    protected $fillable = ['factura_extraccion_id','descripcion','cantidad','precio_unitario','importe','moneda'];
    protected $casts = ['cantidad'=>'decimal:4','precio_unitario'=>'decimal:2','importe'=>'decimal:2'];
    public function extraccion(){ return $this->belongsTo(FacturaExtraccion::class, 'factura_extraccion_id'); }
}
En app/Models/Archivo.php agrega:

php
Mostrar siempre los detalles

Copiar código
public function facturaExtraccion()
{
    return $this->hasOne(\App\Models\FacturaExtraccion::class, 'archivo_id');
}
Flujo de uso
Subir PDFs a un lote (carpeta) desde “archivos generales”.

En la vista del lote, click en Procesar + Importar:

Laravel llama a FastAPI → procesa PDFs → devuelve JSON.

Laravel inserta/actualiza factura_extracciones + factura_items.

Ir a Resultados del lote: tabla de facturas + mini dashboard.

Endpoints y rutas
FastAPI
bash
Mostrar siempre los detalles

Copiar código
GET /lotes/{lote}/facturas?limit=10&offset=0&save=false
Respuesta (ejemplo):

json
Mostrar siempre los detalles

Copiar código
{
  "ok": true,
  "lote": "lote1",
  "total_pdfs": 12,
  "procesados_en_esta_tanda": 5,
  "offset": 0,
  "limit": 5,
  "next_offset": 5,
  "resultados": {
    "archivo.pdf": {
      "nro_factura": "A-0001-00001234",
      "fecha_factura": "2025-08-30",
      "nombre_persona": "ACME S.A.",
      "moneda": "ARS",
      "totales": { "base_imponible":"1000.00","subtotal":"1000.00","iva":"210.00","total":"1210.00" },
      "items": [ { "cantidad":"2.00","descripcion":"Servicio","precio_unitario":"500.00","importe":"1000.00","moneda":"ARS" } ]
    }
  }
}
Laravel
POST /archivos/facturas/lote/{carpeta}/procesar-importar → llama a FastAPI (tandas opcionales) y guarda en BD.

GET /archivos/facturas/lote/{carpeta}/resultados → lista + dashboard del lote.

Matching de archivos: como el nombre físico puede ser aleatorio, la importación busca por nombre_original o por coincidencia del nombre físico dentro de ruta (... LIKE '%/<pdfName>'). Recomendado: agregar campo nombre_fisico al subir.

Dashboard por lote
Incluye:

Tarjetas: Subtotal, Base imponible, IVA, Total.

KPIs: PDFs totales, procesadas, sin procesar, promedio de “Total”, inconsistencias, total de ítems.

Gráfico 1 (línea): evolución diaria de Subtotal, IVA y Total.

Gráfico 2 (donut): Procesadas vs. Sin procesar.

Top personas: top 5 por suma de “Total”.

Tabla de facturas con despliegue de ítems.

Charts con Chart.js (CDN) y carga segura (DOMContentLoaded).

Normalización de números y fechas
FastAPI normaliza:

Decimales con punto y sin miles (p. ej. "1241.46").

Fechas YYYY-MM-DD.

Strings sin saltos de línea duplicados.

Laravel puede castear directo a float o usar helper parseNumber() para entradas mixtas.

Rendimiento y timeouts
PHP suele cortar a 30 s. Opciones:

En controller: @set_time_limit(0) / @ini_set('max_execution_time','0').

Servidor:

php.ini: max_execution_time = 600

PHP-FPM: request_terminate_timeout = 600

Nginx: fastcgi_read_timeout 600s

Apache: Timeout 600

Tandas: usar ?limit=10&offset=… para lotes grandes.

Queues: mover el proceso a Jobs de Laravel y mostrar progreso.

Troubleshooting
405 Method Not Allowed: método/param path distinto a lo registrado.

404 /health: estás levantando otro archivo; corré uvicorn <archivo>:app.

json.loads falla: limpia fences ``` y comas finales; si el PDF es escaneado, agregá OCR (p. ej. pytesseract).

No se insertan datos: no matchea el PDF → usar ruta LIKE '%/<pdf>' o agregar nombre_fisico.

getContext of null: el script corre antes del <canvas> → usar DOMContentLoaded y chequear elementos.

Parse errors en Blade: si PHP < 8.0, evitá nullsafe ?-> y arrow functions.

Roadmap / Ideas
OCR fallback para PDFs escaneados.

Estados de factura (pendiente, validada, pagada) y vencimientos.

Terceros (proveedores/clientes) normalizados y mapeo fuzzy.

Notas de crédito/débito y conciliación.

Conciliación bancaria con extractos CSV.

Exportar CSV/Excel y API para BI (PowerBI/Looker).

Reprocesar 1 PDF puntual desde la tabla.