import React from "react";
import ReactDOM from "react-dom/client";
import App from "./App.jsx";
import "./index.css";

// ตรวจสอบก่อนว่า element ที่ต้องการมีอยู่จริง
/* 
if (rootElement) ช่วยให้แน่ใจว่าโค้ด React จะไม่พยายาม render หากไม่พบ element เป้าหมายในหน้านั้น (ซึ่งเป็นสิ่งที่สำคัญเมื่อใช้งานใน WordPress เนื่องจากไม่ทุกหน้าจะมี element นี้).

*/
const isDevelopment = import.meta.env.MODE === "development";
const rootElement = document.getElementById(
  isDevelopment ? "root" : `my-react-app`
);

if (rootElement) {
  ReactDOM.createRoot(rootElement).render(
    <React.StrictMode>
      <App />
    </React.StrictMode>
  );
}
