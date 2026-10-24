import React from "react";
import { createRoot } from "react-dom/client";
import { BuilderApp } from "./BuilderApp.jsx";
import "./builder.css";

const root = document.getElementById("builder-root");
const boot = document.getElementById("builder-bootstrap");
if (root && boot) {
  try {
    createRoot(root).render(
      <BuilderApp bootstrap={JSON.parse(boot.textContent)} />,
    );
  } catch {
    root.textContent =
      "The design workspace could not load. Reload this page to try again.";
  }
}
