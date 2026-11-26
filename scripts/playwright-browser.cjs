"use strict";

const { chromium } = require("playwright");

async function main() {
  const [mode, target, widthValue, heightValue, output] = process.argv.slice(2);
  const width = Number.parseInt(widthValue, 10);
  const height = Number.parseInt(heightValue, 10);
  if (!mode || !target || !Number.isInteger(width) || !Number.isInteger(height)) {
    throw new Error("Usage: playwright-browser.cjs dump|screenshot URL WIDTH HEIGHT [OUTPUT]");
  }

  const browser = await chromium.launch({
    headless: true,
    args: ["--allow-file-access-from-files"],
  });
  try {
    const reducedMotion = new URL(target).searchParams.has("reduced") ? "reduce" : "no-preference";
    const context = await browser.newContext({ viewport: { width, height }, reducedMotion });
    const page = await context.newPage();
    await page.goto(target, { waitUntil: "load" });
    await page.waitForTimeout(500);

    if (mode === "dump") {
      process.stdout.write(await page.content());
      return;
    }
    if (mode === "screenshot" && output) {
      await page.screenshot({ path: output });
      return;
    }
    throw new Error("Screenshot mode requires an output path.");
  } finally {
    await browser.close();
  }
}

main().catch((error) => {
  console.error(error instanceof Error ? error.message : String(error));
  process.exit(1);
});
