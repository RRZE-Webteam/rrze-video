import apiFetch from "@wordpress/api-fetch";
import { Video, ApiResponse, OEmbedData } from "./types"; 

// Function to send URL to API
export const sendUrlToApi = async (url: string, id: number, rand: string): Promise<ApiResponse> => {
  if (id && typeof id === "string") {
    id = parseInt(id);
  }

  if (!id && !url && !rand) {
    console.log("No URL or ID or rand provided");
    return;
  }
  if (!id && url && url !== "") {
    try {
      const response = await apiFetch<ApiResponse>({
        path: "/rrze-video/v1/process-url",
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          url: url,
        }),
      });

      return response;
    } catch (error) {
      console.error("API Request Error:", error);
      throw new Error(
        "Fehler: Sie müssen angemeldet sein, um diese Funktion zu nutzen."
      );
    }
  }
  if (id) {
    try {
      const response = await apiFetch<ApiResponse>({
        path: "/rrze-video/v1/process-id",
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          id: id,
        }),
      });

      return response;
    } catch (error) {
      console.error("API Request Error:", error);
      throw new Error(
        "Fehler: Sie müssen angemeldet sein, um diese Funktion zu nutzen."
      );
    }
  }
  if (rand) {
    try {
      const response = await apiFetch<ApiResponse>({
        path: "/rrze-video/v1/process-id",
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          rand: rand,
        }),
      });

      return response;
    } catch (error) {
      console.error("API Request Error:", error);
      throw new Error(
        "Fehler: Sie müssen angemeldet sein, um diese Funktion zu nutzen."
      );
    }
  }
};