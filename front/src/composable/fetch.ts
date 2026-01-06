import { ref } from "vue";

interface ApiOptions {
  method?: "GET" | "POST" | "PUT" | "DELETE";
  body?: any;
  headers?: Record<string, string>;
  token?: string | null;
}

export function useApi<T = any>() {
  const data = ref<T | null>(null);
  const error = ref<string | null>(null);
  const loading = ref(false);

  const callApi = async (url: string, options: ApiOptions = {}) => {
    loading.value = true;
    error.value = null;

    try {
      const fetchOptions: RequestInit = {
        method: options.method ?? "GET",
        headers: {
          "Content-Type": "application/json",
          ...(options.token ? { Authorization: `Bearer ${options.token}` } : {}),
          ...(options.headers ?? {}),
        },
      };

      if (options.body) {
        fetchOptions.body = JSON.stringify(options.body);
      }

      const response = await fetch(url, fetchOptions);

      let responseBody: any;
      const contentType = response.headers.get("content-type");

      // Lire le corps une seule fois
      if (contentType && contentType.includes("application/json")) {
        responseBody = await response.json();
      } else {
        responseBody = await response.text();
      }

      if (!response.ok) {
        let message = `HTTP error ${response.status}`;

        try {
          if (typeof responseBody === "object") {
            if (responseBody.message) message = responseBody.message;
            else if (responseBody.errors && Array.isArray(responseBody.errors)) message = responseBody.errors[0];
          } else if (responseBody) {
            message = responseBody;
          }
        } catch {
          // fallback
        }

        throw new Error(message);
      }

      data.value = responseBody;
    } catch (err: any) {
      error.value = err.message ?? "Unexpected error";
    } finally {
      loading.value = false;
    }
  };

  return {
    data,
    error,
    loading,
    callApi,
  };
}