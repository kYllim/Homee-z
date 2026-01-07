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

  const callApi = async (url: string, options: ApiOptions = {}): Promise<T> => {
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

      // parse the response body exactly once and reuse it
      const contentType = response.headers.get("content-type") || "";
      let responseBody: any = null;

      if (contentType.includes("application/json")) {
        try {
          responseBody = await response.json();
        } catch (e) {
          responseBody = null;
        }
      } else {
        responseBody = await response.text();
      }

      if (!response.ok) {
        let message = `HTTP error ${response.status}`;
        if (responseBody && typeof responseBody === 'object' && responseBody.message) {
          message = String(responseBody.message);
        }
        throw new Error(message);
      }

      data.value = responseBody;
      return responseBody;
    } catch (err: any) {
      error.value = err.message ?? "Unexpected error";
      throw err; 
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
