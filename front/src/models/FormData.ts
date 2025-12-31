import type { Ref } from "vue";

export interface FormConnexion {
  [key: string]: Ref<string>;
  emailConnexion: Ref<string>;
  passwordConnexion: Ref<string>;
}
export interface ErrorsConexion {
  [key: string]: Ref<string>;
  emailConnexion: Ref<string>;
  passwordConnexion: Ref<string>;
}
export interface FormRegister {
  [key: string]: Ref<string>
  emailRegister: Ref<string>;
  passwordRegister: Ref<string>;
  passwordComfirmation: Ref<string>;
  firstName: Ref<string>;
  name: Ref<string>;
}
export interface ErrorRegister {
  [key: string]: Ref<string>;
  emailRegister: Ref<string>;
  passwordRegister: Ref<string>;
  passwordComfirmation: Ref<string>;
  firstName: Ref<string>;
  name: Ref<string>;
}


export interface JoinHouseHoldData {
  [key: string]: Ref<string>;
  CodeHouseHold : Ref<string>,
}

export interface JoinHouseHoldDataError { 
  [key: string]: Ref<string>;
  CodeHouseHoldError : Ref<string>
}

export interface CreateHouseHoldData { 
  [key: string]: Ref<string>;
  NameHouseHold : Ref<string>,
}

export interface CreateHouseHoldDataError { 
  [key: string]: Ref<string>;
  NameHouseHoldError : Ref<string>
}
