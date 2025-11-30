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

      if (!response.ok) {
        let message = `HTTP error ${response.status}`;

        try {
          const json = await response.json();
          if (json.message) message = json.message;
          else if (json.errors && Array.isArray(json.errors)) message = json.errors[0];
        } catch {
          const text = await response.text();
          if (text) message = text;
        }

        throw new Error(message);
      }

      data.value = await response.json();
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