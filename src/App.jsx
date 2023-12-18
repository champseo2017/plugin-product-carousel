import { useState, useEffect } from "react";
import reactLogo from "./assets/react.svg";
import viteLogo from "/vite.svg";
import "./App.css";
import axios from "axios";

const isDevelopment = import.meta.env.MODE === "development";

const endPointApi = import.meta.env.VITE_API_ENDPOINT;

const App = () => {
  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${endPointApi}/wp-json/product-carousel/api/v1/testGet`
        );
        console.log("response", response);
      } catch (error) {
        console.error("Error fetching data: ", error);
      }
    };

    fetchData();
  }, []);

  return (
    <>
      <div className="App">
        <h1>Welcome to My WordPress Plugin!</h1>
      </div>
    </>
  );
};

export default App;
