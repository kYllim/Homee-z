import type { FormErrors, FormData } from "../model";
import { ref, type Ref } from "vue";

export function isEmailValid(email: string): boolean {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

export function isPasswordValid(password: string): boolean {
  const passwordRegex = /^(?=.{8,40}$)(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/;
  return passwordRegex.test(password);
}

export function PasswordMatching(
  password: string,
  confirmation: string
): boolean {
  return password === confirmation;
}

export function resetForm(refForm: FormData, errorForm: FormErrors) {
  for (let key in refForm) {
    const k = key as keyof FormData;
    (refForm[k] as Ref<string>).value = "";
  }
  for (let key in errorForm) {
    const k = key as keyof FormErrors;
    (refForm[k] as Ref<string>).value = "";
  }
}
