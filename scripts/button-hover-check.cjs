"use strict";

const { chromium } = require("playwright");
const { resolve } = require("node:path");
const { pathToFileURL } = require("node:url");

async function main() {
  const publicDirectory = resolve(process.argv[2] || "public");
  const captures = process.argv[3];
  const browser = await chromium.launch({ args: ["--allow-file-access-from-files"] });
  const check = (condition, label) => { if (!condition) throw new Error(label); };
  try {
    for (const css of ["dct.css", "dct.min.css"]) {
      for (const theme of ["light", "dark"]) {
        for (const reducedMotion of ["no-preference", "reduce"]) {
          for (const width of [390, 1440]) {
            const context = await browser.newContext({ viewport: { width, height: 844 }, colorScheme: theme, reducedMotion });
            const page = await context.newPage();
            await page.goto(pathToFileURL(resolve(publicDirectory, "index.html")).href);
            const cssUrl = pathToFileURL(resolve(publicDirectory, "downloads", css)).href;
            await page.setContent(`<!doctype html><html lang="en" data-dct-theme="${theme}"><head><meta charset="utf-8"><link rel="stylesheet" href="${cssUrl}"></head><body>
              <main class="dct-container dct-p-6"><div class="dct-cluster" id="buttons">
                <button class="dct-button dct-is-hover-elevated" id="neutral" type="button">Neutral</button>
                <button class="dct-button dct-is-primary dct-is-hover-elevated" id="primary" type="button">Save</button>
                <button class="dct-button dct-is-secondary dct-is-hover-elevated" id="secondary" type="button">Details</button>
                <button class="dct-button dct-is-danger dct-is-hover-elevated" id="danger" type="button">Delete</button>
                <a class="dct-button dct-is-hover-elevated" id="link" href="#buttons">Documentation</a>
                <button class="dct-button" id="plain" type="button">Unmodified</button>
                <button class="dct-button dct-is-hover-elevated" id="disabled" type="button" disabled>Disabled</button>
                <button class="dct-button dct-is-hover-elevated dct-is-loading" id="loading" type="button" disabled aria-busy="true">Loading</button>
                <a class="dct-button dct-is-hover-elevated" id="disabled-link" href="#buttons" aria-disabled="true" tabindex="-1">Unavailable</a>
              </div><div id="shadow-reference" style="box-shadow: var(--dct-shadow-sm)"></div></main>
            </body></html>`, { waitUntil: "load" });
            const stem = `${css.replaceAll(".", "-")}-${width}-${theme}-${reducedMotion}`;
            const reference = await page.locator("#shadow-reference").evaluate(el => getComputedStyle(el).boxShadow);
            check(reference !== "none", `${stem}: missing shadow token`);
            check(await page.evaluate(() => matchMedia("(hover: hover)").matches), `${stem}: hover unavailable`);
            await page.keyboard.press("Tab");
            check(await page.locator("#neutral").evaluate(el => el.matches(":focus-visible") && getComputedStyle(el).outlineStyle !== "none"), `${stem}: lost keyboard focus`);
            await page.locator("#neutral").blur();
            if (captures) await page.locator("main").screenshot({ path: resolve(captures, `${stem}-before.png`) });
            for (const id of ["neutral", "primary", "secondary", "danger", "link"]) {
              const button = page.locator(`#${id}`);
              check(await button.evaluate(el => getComputedStyle(el).boxShadow === "none"), `${stem}: ${id} shadow outside hover`);
              const before = await button.boundingBox();
              const layoutTop = await button.evaluate(el => el.offsetTop);
              await button.hover();
              await page.waitForFunction(({ id, reference }) => getComputedStyle(document.getElementById(id)).boxShadow === reference, { id, reference });
              const after = await button.boundingBox();
              check(["x", "width", "height"].every(key => Math.abs(before[key] - after[key]) <= 0.1), `${stem}: ${id} hover changes size or horizontal position`);
              const lift = reducedMotion === "reduce" ? 0 : 1;
              check(Math.abs(after.y - before.y + lift) <= 0.1, `${stem}: ${id} incorrect hover lift`);
              check(await button.evaluate(el => el.offsetTop) === layoutTop, `${stem}: ${id} hover changes document layout`);
              if (captures && id === "neutral") await page.locator("main").screenshot({ path: resolve(captures, `${stem}-hover.png`) });
              if (id === "neutral") {
                await page.mouse.down();
                await page.waitForFunction(id => getComputedStyle(document.getElementById(id)).transform === "matrix(1, 0, 0, 1, 0, 1)", id);
                check(await button.evaluate(el => el.matches(":active")), `${stem}: active state missing`);
                await page.mouse.up();
              }
              await page.mouse.move(0, 0);
              await page.waitForFunction(id => { const style = getComputedStyle(document.getElementById(id)); return style.boxShadow === "none" && style.transform === "none"; }, id);
            }
            for (const id of ["plain", "disabled", "loading", "disabled-link"]) {
              await page.locator(`#${id}`).hover({ force: true });
              check(await page.locator(`#${id}`).evaluate(el => getComputedStyle(el).boxShadow === "none"), `${stem}: excluded ${id} receives shadow`);
              check(await page.locator(`#${id}`).evaluate(el => getComputedStyle(el).transform === "none"), `${stem}: excluded ${id} moves on hover`);
            }
            if (reducedMotion === "reduce") {
              check(await page.locator("#neutral").evaluate(el => getComputedStyle(el).transitionDuration.split(",").every(value => parseFloat(value) <= 0.0001)), `${stem}: reduced motion ignored`);
            }
            await page.mouse.move(0, 0);
            await page.evaluate(() => document.documentElement.style.setProperty("--dct-shadow-sm", "0 2px 4px rgb(20 40 60 / 0.4)"));
            const customized = await page.locator("#shadow-reference").evaluate(el => getComputedStyle(el).boxShadow);
            await page.locator("#neutral").hover();
            await page.waitForFunction(shadow => getComputedStyle(document.getElementById("neutral")).boxShadow === shadow, customized);
            check(customized !== reference, `${stem}: token override ignored`);
            await page.emulateMedia({ forcedColors: "active" });
            check(await page.locator("#neutral").evaluate(el => getComputedStyle(el).boxShadow === "none"), `${stem}: forced colors shadow remains`);
            await page.keyboard.press("Tab");
            check(await page.evaluate(() => getComputedStyle(document.activeElement).outlineStyle !== "none"), `${stem}: forced colors focus missing`);
            await context.close();
          }
        }
      }
      const touch = await browser.newContext({ viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true });
      const page = await touch.newPage();
      await page.goto(pathToFileURL(resolve(publicDirectory, "components.html")).href);
      const cssUrl = pathToFileURL(resolve(publicDirectory, "downloads", css)).href;
      await page.setContent(`<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" href="${cssUrl}"></head><body><button class="dct-button dct-is-hover-elevated" type="button">Touch action</button></body></html>`, { waitUntil: "load" });
      check(await page.evaluate(() => !matchMedia("(hover: hover)").matches), "Touch context unexpectedly supports hover");
      const button = page.locator(".dct-is-hover-elevated").first();
      await button.tap();
      check(await button.evaluate(el => getComputedStyle(el).boxShadow === "none"), "Touch activation retains a hover shadow");
      await page.waitForFunction(() => getComputedStyle(document.querySelector(".dct-is-hover-elevated")).transform === "none");
      await touch.close();
    }
    console.log("Button hover elevation checks passed (CSS, themes, widths, motion, states, tokens, forced colors and touch).");
  } finally {
    await browser.close();
  }
}

main().catch(error => { console.error(error); process.exit(1); });
