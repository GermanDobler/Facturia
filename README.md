<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>IA FACTURAS — README (HTML)</title>
<style>
  :root{
    --bg:#0b0f14; --card:#121821; --muted:#93a1b0; --text:#e6edf3; --accent:#63b3ed; --line:#243041;
    --green:#34d399; --yellow:#fbbf24; --red:#f87171; --cyan:#22d3ee; --purple:#c084fc;
  }
  *{box-sizing:border-box}
  body{margin:0; font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, sans-serif; background:var(--bg); color:var(--text); line-height:1.55}
  header{padding:36px 20px 20px; background:linear-gradient(135deg, #0b1320, #0d1825)}
  .wrap{max-width: 980px; margin:0 auto; padding:0 16px}
  h1{margin:0 0 8px; font-size:32px}
  h2{margin:32px 0 12px; font-size:22px; border-bottom:1px solid var(--line); padding-bottom:6px}
  h3{margin:24px 0 8px; font-size:18px}
  p{margin:10px 0}
  code, pre{font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace; font-size: 13px}
  pre{background:#0e1520; border:1px solid var(--line); padding:12px; border-radius:8px; overflow:auto}
  code.inline{background:#0e1520; border:1px solid var(--line); padding:2px 6px; border-radius:6px}
  a{color:var(--accent); text-decoration:none}
  a:hover{text-decoration:underline}
  .callout{border:1px solid var(--line); background: #0e1520; padding:12px; border-radius:10px}
  .grid{display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:12px}
  .card{background:var(--card); border:1px solid var(--line); border-radius:12px; padding:14px}
  .muted{color:var(--muted)}
  .tag{display:inline-block; background:#0e1520; border:1px solid var(--line); padding:2px 8px; border-radius:999px; font-size:12px; margin-right:6px}
  .toc{background:var(--card); border:1px solid var(--line); border-radius:12px; padding:14px}
  ul{margin:8px 0 8px 20px}
  li{margin:6px 0}
  .kbd{border:1px solid var(--line); border-bottom-width:2px; background:#0e1520; padding:0 6px; border-radius:6px}
  .footer{margin:40px 0 20px; font-size:13px; color:var(--muted)}
</style>
</head>
<body>
  <header>
    <div class="wrap">
      <h1>IA FACTURAS — README</h1>
      <p class="muted">Pipeline para <strong>subir facturas</strong>, <strong>extraer</strong> con <em>FastAPI + GPT4All</em>, <strong>guardar</strong> en MySQL y <strong>visualizar</strong> en Laravel (mini‑dashboard por lote).</p>
      <div>
        <span class="tag">Laravel</span>
        <span class="tag">FastAPI</span>
        <span class="tag">GPT4All</span>
        <span class="tag">PyMuPDF</span>
        <span class="tag">MySQL</span>
      </div>
    </div>
  </header>

  <main class="wrap">
    <div class="toc">
      <strong>Índice</strong>
      <ul>
        <li><a href="#arquitectura">Arquitectura</a></li>
        <li><a href="#requisitos">Requisitos</a></li>
        <li><a href="#instalacion">Instalación</a></li>
        <li><a href="#config">Configuración (.env)</a></li>
        <li><a href="#estructura">Estructura de carpetas</a></li>
        <li><a href="#bd">Base de datos</a></li>
        <li><a href="#modelos">Modelos</a></li>
        <li><a href="#flujo">Flujo de uso</a></li>
        <li><a href="#endpoints">Endpoints y rutas</a></li>
        <li><a href="#dashboard">Dashboard por lote</a></li>
        <li><a href="#normalizacion">Normalización</a></li>
        <li><a href="#rendimiento">Rendimiento y timeouts</a></li>
        <li><a href="#troubleshooting">Troubleshooting</a></li>
        <li><a href="#roadmap">Roadmap</a></li>
        <li><a href="#licencia">Licencia</a></li>
      </ul>
    </div>

    <h2 id="arquitectura">Arquitectura</h2>
    <div class="grid">
      <div class="card">
        <h3>Laravel</h3>
        <ul>
          <li>Sube PDFs por <em>lote</em> (carpeta) y registra en <code class="inline">archivos</code>.</li>
          <li>Acción <strong>Procesar + Importar</strong>: llama FastAPI e ingesta en BD.</li>
          <li>Vista <strong>Resultados</strong>: tabla + mini‑dashboard.</li>
        </ul>
      </div>
      <div class="card">
        <h3>FastAPI</h3>
        <ul>
          <li>Lee PDFs de <code class="inline">storage/app/.../archivos_facturas/&lt;lote&gt;</code>.</li>
          <li>Extrae texto (PyMuPDF) y estructura con GPT4All.</li>
          <li>Devuelve JSON normalizado. Soporta <code class="inline">limit</code>/<code class="inline">offset</code>.</li>
        </ul>
      </div>
      <div class="card">
        <h3>MySQL</h3>
        <ul>
          <li><code class="inline">factura_extracciones</code></li>
          <li><code class="inline">factura_items</code></li>
        </ul>
      </div>
    </div>

    <h2 id="requisitos">Requisitos</h2>
    <ul>
      <li>PHP 8.0+ y Composer • Laravel 9/10 • MySQL/MariaDB</li>
      <li>Python 3.10+ • <code class="inline">fastapi</code>, <code class="inline">uvicorn</code>, <code class="inline">pymupdf</code>, <code class="inline">gpt4all</code></li>
      <li>Modelo GPT4All (ej. <code class="inline">Nous-Hermes-2-Mistral-7B-DPO.Q4_0.gguf</code>)</li>
    </ul>

    <h2 id="instalacion">Instalación</h2>
    <h3>Laravel</h3>
<pre><code>composer install
cp .env.example .env
php artisan key:generate
# configurar conexión a BD en .env
php artisan migrate
php artisan storage:link
</code></pre>

    <h3>FastAPI</h3>
<pre><code>pip install fastapi uvicorn pymupdf gpt4all
uvicorn server:app --reload --port 8001
</code></pre>
    <div class="callout muted">Ajustá rutas/modelo en <code class="inline">server.py</code>.</div>

    <h2 id="config">Configuración (.env)</h2>
    <h3>Laravel</h3>
<pre><code>FACTURAS_API_URL=http://127.0.0.1:8001
FACTURAS_API_KEY=   # opcional
# ARCHIVOS_FACTURAS=public/archivos_facturas
</code></pre>

    <h3>FastAPI</h3>
<pre><code>BASE_FACTURAS = Path(r"C:\...\IA FACTURA\storage\app\public\archivos_facturas")
MODELO = "Nous-Hermes-2-Mistral-7B-DPO.Q4_0.gguf"
RUTA_MODELO = r"C:\Users\User\AppData\Local\nomic.ai\GPT4All"
</code></pre>

    <h2 id="estructura">Estructura de carpetas</h2>
<pre><code>storage/
  app/
    public/
      archivos_facturas/
        LOTE_2025_09/
          factura1.pdf
          factura2.pdf
        LOTE_PRUEBA/
          ...
</code></pre>

    <h2 id="bd">Base de datos</h2>
    <p>Tablas nuevas:</p>
<pre><code>CREATE TABLE factura_extracciones (...);
CREATE TABLE factura_items (...);
</code></pre>
    <p>Ver SQL completo en el README.md original o migraciones.</p>

    <h2 id="modelos">Modelos</h2>
<pre><code>// app/Models/FacturaExtraccion.php
public function items(){ return $this-&gt;hasMany(FacturaItem::class, 'factura_extraccion_id'); }

// app/Models/FacturaItem.php
public function extraccion(){ return $this-&gt;belongsTo(FacturaExtraccion::class, 'factura_extraccion_id'); }

// app/Models/Archivo.php
public function facturaExtraccion(){ return $this-&gt;hasOne(FacturaExtraccion::class, 'archivo_id'); }
</code></pre>

    <h2 id="flujo">Flujo de uso</h2>
    <ol>
      <li>Subís PDFs a un <strong>lote</strong> (carpeta).</li>
      <li>Click en <strong>Procesar + Importar</strong> (Laravel &rarr; FastAPI &rarr; BD).</li>
      <li>Abrís <strong>Resultados</strong>: tabla + dashboard.</li>
    </ol>

    <h2 id="endpoints">Endpoints y rutas</h2>
    <h3>FastAPI</h3>
<pre><code>GET /lotes/{lote}/facturas?limit=10&amp;offset=0&amp;save=false
</code></pre>
<pre><code>{
  "ok": true,
  "lote": "lote1",
  "total_pdfs": 12,
  "procesados_en_esta_tanda": 5,
  "offset": 0,
  "limit": 5,
  "next_offset": 5,
  "resultados": { ... }
}
</code></pre>

    <h3>Laravel</h3>
    <ul>
      <li><code class="inline">POST /archivos/facturas/lote/{carpeta}/procesar-importar</code></li>
      <li><code class="inline">GET  /archivos/facturas/lote/{carpeta}/resultados</code></li>
    </ul>

    <h2 id="dashboard">Dashboard por lote</h2>
    <ul>
      <li>Tarjetas: Subtotal, Base imponible, IVA, Total.</li>
      <li>KPIs: PDFs totales, procesadas, sin procesar, promedio de Total, inconsistencias, ítems.</li>
      <li>Gráfico línea: evolución diaria de Subtotal, IVA y Total.</li>
      <li>Gráfico donut: Procesadas vs. Sin procesar.</li>
      <li>Top personas (tabla/bar).</li>
    </ul>

    <h2 id="normalizacion">Normalización</h2>
    <ul>
      <li>Decimales con punto y sin miles: <code class="inline">"1241.46"</code>.</li>
      <li>Fechas ISO: <code class="inline">YYYY-MM-DD</code>.</li>
      <li>Strings sin saltos duplicados.</li>
    </ul>

    <h2 id="rendimiento">Rendimiento y timeouts</h2>
    <ul>
      <li>Subí límites: <code class="inline">max_execution_time</code>, <code class="inline">request_terminate_timeout</code>, etc.</li>
      <li>Procesá en <strong>tandas</strong> con <code class="inline">limit/offset</code>.</li>
      <li>Usá <strong>Jobs/Queue</strong> en Laravel para cargas grandes.</li>
    </ul>

    <h2 id="troubleshooting">Troubleshooting</h2>
    <ul>
      <li>405/404: revisar método/paths y que FastAPI corra en el archivo correcto.</li>
      <li><code class="inline">json.loads</code> falla: limpiar fences y comas finales; usar OCR si es escaneado.</li>
      <li>No se insertan datos: emparejar por <code class="inline">nombre_original</code> o por nombre físico en <code class="inline">ruta</code>.</li>
      <li>JS <em>getContext of null</em>: cargar charts tras <span class="kbd">DOMContentLoaded</span>.</li>
    </ul>

    <h2 id="roadmap">Roadmap</h2>
    <ul>
      <li>OCR fallback para PDFs escaneados.</li>
      <li>Estados de factura y vencimientos.</li>
      <li>Terceros normalizados + mapeo fuzzy.</li>
      <li>Notas de crédito/débito y conciliación.</li>
      <li>Conciliación bancaria, exportar a CSV/Excel y API para BI.</li>
      <li>Reprocesar 1 PDF puntual desde la tabla.</li>
    </ul>

    <h2 id="licencia">Licencia</h2>
    <p>Este proyecto se distribuye “tal cual”. Recomendado: MIT.</p>

    <p class="footer">© IA FACTURAS — 2025</p>
  </main>
</body>
</html>