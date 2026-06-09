import { createRoot } from "react-dom/client";
import App from "./presentation/App.tsx";
import "./core/styles/index.css";

createRoot(document.getElementById("root")!).render(<App />);