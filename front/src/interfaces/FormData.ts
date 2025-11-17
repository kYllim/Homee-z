import type { Ref } from "vue";

export default interface FormData {
    emailConnexion?: Ref<string>,
    passwordConnexion?: Ref<string>,
    emailRegister?: Ref<string>,
    passwordRegister?: Ref<string>,
    passwordComfirmation ?:Ref<string>,
    firstName ?: Ref<string>,
    name ?: Ref<string>
}