import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";

// https://vitejs.dev/config/
export default ({ mode }) =>
  defineConfig({
    plugins: [react()],
    // server: {
    //   proxy:
    //     mode === "development"
    //       ? {
    //           "/wp-admin": "http://localhost:10004",
    //           "/wp-content": "http://localhost:10004",
    //         }
    //       : {},
    // },
  });
