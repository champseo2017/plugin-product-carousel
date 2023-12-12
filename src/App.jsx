import { useState } from "react";
import reactLogo from "./assets/react.svg";
import viteLogo from "/vite.svg";
import "./App.css";

const isDevelopment = import.meta.env.MODE === "development";

function App() {
  const [response, setResponse] = useState("");

  const ajaxUrl = isDevelopment
    ? "http://localhost:10004/wp-admin/admin-ajax.php"
    : myReactApp.ajax_url;

  if (!isDevelopment) {
    console.log("myReactApp", myReactApp);
  }

  const sendAjaxRequest = () => {
    const params = new URLSearchParams({
      action: "my_react_action",
    });

    if (!isDevelopment && myReactApp && myReactApp.nonce) {
      params.append("nonce", myReactApp.nonce);
    }

    fetch(ajaxUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: params,
    })
      .then((response) => response.json())
      .then((data) => {
        setResponse(data);
      })
      .catch((error) => console.error("Error:", error));
  };

  return (
    <>
      <div className="App">
        <h1>Welcome to My WordPress Plugin!</h1>
        <button onClick={sendAjaxRequest}>Send AJAX Request</button>
        <div>Response: {JSON.stringify(response)}</div>
      </div>
    </>
  );
}

export default App;
