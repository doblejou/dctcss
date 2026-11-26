"use strict";

const { chromium, firefox, webkit } = require("playwright");
const { readFileSync, mkdirSync, writeFileSync } = require("node:fs");
const { resolve } = require("node:path");

// This fixture deliberately inherits the integrating application's global rule.
const fixture = `<!doctype html><html lang="es"><meta charset="utf-8">
<style>body { overflow-wrap: anywhere; margin: 16px; }
.app-amount { white-space: nowrap; }
.narrow { inline-size: 32px; overflow: auto; }
.grid { display: grid; grid-template-columns: minmax(0, 1fr); }
</style><body><div class="grid">
<div class="dct-table-wrap" tabindex="0" role="region" aria-labelledby="caption">
<table class="dct-table"><caption class="dct-table-caption" id="caption">Importes y estados</caption>
<thead><tr><th scope="col">Descripción</th><th scope="col" class="dct-is-numeric">Importe</th><th scope="col">Estado</th></tr></thead>
<tbody><tr><td id="description">Descripción extensa que debe poder ajustarse a varias líneas y referencia SinEspaciosParaComprobarElAjusteDeDescripcionesLargas</td><td class="dct-is-numeric app-amount" id="amount">-999.999.999.999,99 EUR</td><td><span class="dct-badge" id="short">Activo</span></td></tr>
<tr><td>Otra descripción larga con información adicional</td><td class="dct-is-numeric" id="mixed">128 EUR, completado con una explicación larga que admite varias líneas</td><td><span class="dct-badge" id="long">Pendiente de comprobación administrativa</span></td></tr></tbody></table></div></div>
<p>Contenedor de 32 px: el badge largo necesita espacio o desplazamiento local.</p>
<div class="narrow"><span class="dct-badge" id="narrow-badge">Pendiente de comprobación administrativa</span></div>
<p>Integración con mínimo reducido a cero; la cifra sin espacios debe seguir íntegra.</p>
<div class="dct-table-wrap" id="compression" tabindex="0" role="region" aria-labelledby="compression-caption">
<table class="dct-table" style="--dct-table-min: 0"><caption class="dct-table-caption" id="compression-caption">Mínimo de aplicación</caption>
<thead><tr><th scope="col">Referencia</th><th scope="col">Cliente</th><th scope="col">Descripción</th><th scope="col" class="dct-is-numeric">Importe</th><th scope="col">Estado</th></tr></thead>
<tbody><tr><td>GEST-2026-1042</td><td>Cooperativa del Norte</td><td>Descripción larga que debe ajustarse con datos representativos de la aplicación</td><td class="dct-is-numeric" id="digits">-999.999.999.999,99</td><td><span class="dct-badge" id="compressed-badge">Activo</span></td></tr></tbody></table></div></body></html>`;

(async () => {
  const [publicDir = "public", output = "/tmp/opencode/dctcss-table-text", mode = "check"] = process.argv.slice(2);
  mkdirSync(output, { recursive: true });
  const results = [];
  for (const [name, engine] of Object.entries({ chromium, firefox, webkit })) {
    const browser = await engine.launch();
    console.log(`${name}: ${browser.version()}`);
    try {
      for (const build of ["dct.css", "dct.min.css"]) {
        const css = readFileSync(resolve(publicDir, "downloads", build), "utf8");
        for (const width of [320, 375]) {
          for (const scale of ["normal", "text-200", "zoom-200"]) {
            const page = await browser.newPage({ viewport: { width, height: 900 }, deviceScaleFactor: 1, hasTouch: true, locale: "es-ES", reducedMotion: "reduce" });
            await page.setContent(fixture);
            await page.addStyleTag({ content: css });
            // Diagnostic override reconstructs the inherited 1.6.1 text behavior.
            if (mode === "diagnose") await page.addStyleTag({ content: ".dct-badge { white-space: normal; } .dct-table th, .dct-table :is(th, td).dct-is-numeric { overflow-wrap: inherit; }" });
            if (scale !== "normal") await page.addStyleTag({ content: scale === "text-200" ? "html { font-size: 200%; }" : "body { zoom: 2; }" });
            const metrics = await page.evaluate(() => {
              const lines = (element) => {
                const range = document.createRange();
                range.selectNodeContents(element);
                return new Set(Array.from(range.getClientRects(), rect => Math.round(rect.top))).size;
              };
              const wrap = document.querySelector(".dct-table-wrap");
              const numeric = document.getElementById("mixed");
              return {
                scroll: wrap.scrollWidth > wrap.clientWidth,
                pageOverflow: document.documentElement.scrollWidth > document.documentElement.clientWidth + 1,
                badgeLines: lines(document.getElementById("short")),
                longLines: lines(document.getElementById("long")),
                narrowLines: lines(document.getElementById("narrow-badge")),
                amountLines: lines(document.getElementById("amount")),
                headerLines: [...wrap.querySelectorAll("thead th")].map(lines),
                descriptionLines: lines(document.getElementById("description")),
                mixedLines: lines(numeric),
                mixedWhiteSpace: getComputedStyle(numeric).whiteSpace,
                mixedOverflowWrap: getComputedStyle(numeric).overflowWrap,
                narrowScroll: document.querySelector(".narrow").scrollWidth > 32,
                digitsLines: lines(document.getElementById("digits")),
                compressedBadgeLines: lines(document.getElementById("compressed-badge")),
                compressedHeaderLines: [...document.querySelectorAll("#compression thead th")].map(lines),
                compressedScroll: document.getElementById("compression").scrollWidth > document.getElementById("compression").clientWidth,
              };
            });
            await page.keyboard.press("Tab");
            metrics.keyboardFocus = await page.locator(".dct-table-wrap").first().evaluate(el => el === document.activeElement && getComputedStyle(el).outlineStyle !== "none");
            await page.keyboard.press("ArrowRight");
            await page.waitForFunction(() => document.querySelector(".dct-table-wrap").scrollLeft > 0);
            metrics.keyboardScroll = true;
            await page.locator(".dct-table-wrap").first().evaluate(el => { el.scrollLeft = 0; });
            const label = `${name}-${build}-${width}-${scale}`;
            await page.screenshot({ path: resolve(output, `${label}.png`), fullPage: true });
            await page.locator(".dct-table-wrap").first().evaluate(el => { el.scrollLeft = el.scrollWidth; });
            await page.locator(".dct-table-wrap").first().screenshot({ path: resolve(output, `${label}-end.png`) });
            results.push({ label, ...metrics });
            if (mode !== "diagnose" && (!metrics.scroll || metrics.pageOverflow || metrics.badgeLines !== 1 || metrics.longLines !== 1 || metrics.narrowLines !== 1 || metrics.amountLines !== 1 || metrics.headerLines.some(n => n !== 1) || metrics.descriptionLines < 2 || metrics.mixedLines < 2 || metrics.mixedWhiteSpace !== "normal" || metrics.mixedOverflowWrap !== "normal" || !metrics.narrowScroll || !metrics.keyboardFocus || metrics.digitsLines !== 1 || metrics.compressedBadgeLines !== 1 || metrics.compressedHeaderLines.some(n => n !== 1) || !metrics.compressedScroll)) {
              throw new Error(`${label}: ${JSON.stringify(metrics)}`);
            }
            await page.close();
          }
        }
      }
    } finally { await browser.close(); }
  }
  writeFileSync(resolve(output, "results.json"), JSON.stringify(results, null, 2));
  console.log(`${results.length} table text cases ${mode === "diagnose" ? "recorded" : "passed"}; screenshots: ${output}`);
})().catch(error => { console.error(error); process.exit(1); });
