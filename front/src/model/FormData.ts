import type { Ref } from "vue";

export interface FormData {
  emailConnexion: Ref<string>;
  emailRegister: Ref<string>;
  passwordRegister: Ref<string>;
  passwordComfirmation: Ref<string>;
  passwordConnexion: Ref<string>;
  firstName: Ref<string>;
  name: Ref<string>;
}

export interface FormErrors {
  emailConnexion: Ref<boolean>;
  emailRegister: Ref<boolean>;
  passwordRegister: Ref<boolean>;
  passwordComfirmation: Ref<boolean>;
  passwordConnexion: Ref<boolean>;
  firstName: Ref<boolean>;
  name: Ref<boolean>;
}
