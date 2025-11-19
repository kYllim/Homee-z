import type { Ref } from "vue";

export interface FormConnexion {
  emailConnexion: Ref<string>;
  passwordConnexion: Ref<string>;
}
export interface ErrorsConexion {
  emailConnexion: Ref<string>;
  passwordConnexion: Ref<string>;
}
export interface FormRegister {
  emailRegister: Ref<string>;
  passwordRegister: Ref<string>;
  passwordComfirmation: Ref<string>;
  firstName: Ref<string>;
  name: Ref<string>;
}
export interface ErrorRegister {
  emailRegister: Ref<string>;
  passwordRegister: Ref<string>;
  passwordComfirmation: Ref<string>;
  firstName: Ref<string>;
  name: Ref<string>;
}
