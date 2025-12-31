import type { ErrorRegister,ErrorsConexion,FormConnexion,FormRegister } from "../models";
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

export function resetError<T extends Record<string, Ref<string>>>(
  errorForm: T
) {
  Object.values(errorForm).forEach(e => (e.value = ""));
}

export function isBlank<
  T extends Record<string, Ref<string>>,
  E extends Record<keyof T, Ref<string>>
>(form: T, errors: E): boolean {
  let valid = true;

  for (const key in form) {
    const field = form[key];  
    const err = errors[key];   
    if (!field || !err) continue;
    if (field.value.trim() === "") {
      err.value = "This field is empty !";
      valid = false;
    }
  }

  return valid;
}
