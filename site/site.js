"use strict";

const statusRegion = document.querySelector("[data-site-status]");
const copyResetTimers = new WeakMap();
const copyOriginalLabels = new WeakMap();
let statusTimer;

document.querySelectorAll("[data-site-copy]").forEach((button) => {
  copyOriginalLabels.set(button, button.textContent);
  button.hidden = false;
});

function setStatus(message) {
  if (!statusRegion) return;
  window.clearTimeout(statusTimer);
  statusRegion.textContent = "";
  statusTimer = window.setTimeout(() => {
    statusRegion.textContent = message;
  }, 20);
}

document.addEventListener("click", async (event) => {
  const copyButton = event.target.closest("[data-site-copy]");
  if (copyButton) {
    const code = document.getElementById(copyButton.dataset.siteCopy);
    if (!code) return;

    const originalText = copyOriginalLabels.get(copyButton);
    const context = copyButton.getAttribute("aria-label")?.replace(/^Copiar\s+/i, "") || "codigo";

    try {
      await navigator.clipboard.writeText(code.textContent);
      copyButton.textContent = "Copiado";
      setStatus(`Se ha copiado ${context} al portapapeles.`);
    } catch {
      const selection = window.getSelection();
      if (!selection) {
        setStatus(`No se pudo copiar ${context} automáticamente.`);
        return;
      }
      const range = document.createRange();
      range.selectNodeContents(code);
      selection.removeAllRanges();
      selection.addRange(range);
      setStatus(`No se pudo copiar ${context} automáticamente. El código queda seleccionado para copiarlo manualmente.`);
    }

    window.clearTimeout(copyResetTimers.get(copyButton));
    const resetTimer = window.setTimeout(() => {
      copyButton.textContent = originalText;
      copyResetTimers.delete(copyButton);
    }, 2000);
    copyResetTimers.set(copyButton, resetTimer);
    return;
  }

  const dialogButton = event.target.closest("[data-site-dialog-open]");
  if (dialogButton) {
    const dialog = document.getElementById(dialogButton.dataset.siteDialogOpen);
    if (dialog instanceof HTMLDialogElement) {
      dialog.addEventListener("close", () => window.setTimeout(() => dialogButton.focus()), { once: true });
      dialog.showModal();
    }
  }
});

const desktopNavbar = window.matchMedia("(min-width: 64rem)");

document.querySelectorAll("[data-site-navbar-toggle]").forEach((toggle) => {
  const menu = document.getElementById(toggle.getAttribute("aria-controls"));
  if (!menu) return;

  const syncNavbar = () => {
    const expanded = desktopNavbar.matches;
    toggle.hidden = expanded;
    toggle.setAttribute("aria-expanded", String(expanded));
    menu.hidden = !expanded;
  };

  toggle.addEventListener("click", () => {
    if (desktopNavbar.matches) return;
    const expanded = toggle.getAttribute("aria-expanded") !== "true";
    toggle.setAttribute("aria-expanded", String(expanded));
    menu.hidden = !expanded;
  });

  desktopNavbar.addEventListener("change", syncNavbar);
  syncNavbar();
});

const applicationDialogTrigger = document.getElementById("application-dialog-trigger");
const applicationDialog = document.getElementById("application-dialog");
if (applicationDialogTrigger && applicationDialog instanceof HTMLDialogElement) {
  applicationDialogTrigger.addEventListener("click", () => applicationDialog.showModal());
  applicationDialog.addEventListener("close", () => window.setTimeout(() => applicationDialogTrigger.focus()));
}
