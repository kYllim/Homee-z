<script setup lang="ts">
  import { ref,watch } from "vue";
  import type { Ref } from "vue";
  import { useRouter,useRoute } from "vue-router";
  import type { FormConnexion, ErrorsConexion, FormRegister, ErrorRegister } from "../model";
  import NavBarLanding from "@/components/NavBarLanding.vue";
  import ConnexionForm from "@/components/Auth/ConnexionForm.vue";
  import InscriptionForm from "@/components/Auth/InscriptionForm.vue";

  const props = defineProps({
    mode: {
      type: String,
      default: "connexion",
    }
  });

  const router = useRouter();
  const route = useRoute();

  watch(
    () => route.query.mode,
    (newMode) => {
      FormType.value = newMode === "inscription" ? "inscription" : "connexion";
    }
  );

  const FormType: Ref<"connexion" | "inscription"> = ref(props.mode === "inscription" ? "inscription" : "connexion");

  // Ref pour le form de connexion
  const ConnexionFormRef: FormConnexion = {
    emailConnexion: ref(""),
    passwordConnexion: ref(""),
  };
  const ConnexionFormError: ErrorsConexion = {
    emailConnexion: ref(""),
    passwordConnexion: ref(""),
  }

  // Ref pour le form d'inscription
  const RegisterFormRef : FormRegister = {
    emailRegister: ref(""),
    passwordRegister: ref(""),
    passwordComfirmation : ref(""),
    firstName : ref(''),
    name : ref('')
  };

  const RegisterFormError: ErrorRegister = {
    emailRegister : ref(''),
    passwordRegister: ref(""),
    passwordComfirmation: ref(""),
    name: ref(""),
    firstName: ref(""),
  }

  // Gérér l'affichage des forms 
  const toggleDisplay = () => {
    FormType.value =
    FormType.value === "connexion" ? "inscription" : "connexion";
    router.replace({ path: "/connexion", query: { mode: FormType.value } });
  };

</script>

<template>
  <div>
    <NavBarLanding/>
    <div v-if="FormType === 'connexion'" class="flex flex-row items-center justify-center w-full mt-6">
      <div class="hidden lg:flex flex-col p-4 ml-2">
        <h2 class="font-regular text-5xl mb-2">Join ChoreFlow</h2>
        <p class="font-regular text-lg text-lighBlue">Manage your household tasks efficiently and never miss a chore again.</p>
        <div class="flex flex-col gap-4 mt-4">
          <!-- une feature -->
          <div class="flex flex-row gap-2">
            <div class="bg-green_pastel p-2 flex items-center justify-center rounded-md">
              <i class="pi pi-list-check text-green-900" style="font-size: 2rem"></i>
            </div>
            <div class="flex flex-col ">
              <h3 class="text-lg font-semibold">Task Management</h3>
              <p class="text-lighBlue text-md">Create, assign, and track household chores with ease</p>
            </div>
          </div>
          <!-- une feature -->
          <div class="flex flex-row gap-2">
            <div class="bg-beige-pastel p-2 flex items-center justify-center rounded-md">
              <i class="pi pi-users text-black" style="font-size: 2rem"></i>
            </div>
            <div class="flex flex-col ">
              <h3 class="text-lg font-semibold">Family Collaboration</h3>
              <p class="text-lighBlue text-md">Share tasks with family members and stay organized together</p>
            </div>
          </div>
          <!-- une feature -->
          <div class="flex flex-row gap-2">
            <div class="bg-lighBlue p-2 flex items-center justify-center rounded-md">
              <i class="pi pi-bell text-gray-300" style="font-size: 2rem"></i>
            </div>
            <div class="flex flex-col ">
              <h3 class="text-lg font-semibold">Smart Reminders</h3>
              <p class="text-lighBlue text-md">Get notified about upcoming and overdue tasks</p>
            </div>
          </div>
        </div>
      </div>
      <ConnexionForm  :dataConnexion="ConnexionFormRef" :errorConnexion="ConnexionFormError" :toggleDisplay="toggleDisplay"/>
    </div> 
    <div v-else class="flex flex-row items-baseline justify-center w-full mt-6">
      <div class="hidden lg:flex flex-col p-4 ml-2 justify-start pl-28">
        <h2 class="font-regular text-5xl">Organize Your Home,</h2>
        <h2 class="font-regular text-5xl text-green_pastel mb-4">Simplify Your Life</h2>
        <p class="font-regular text-lg text-lighBlue mb-4">
          Join thousands of families who have transformed their household management with ChoreHub. Track tasks, assign  responsibilities, and maintain a harmonious home environment.
        </p>
        <div class="flex flex-row gap-3">
          <div class="flex flex-col items-center justify-center gap-2">
            <div class="bg-beige-pastel p-2  rounded-md w-fit px-4 py-3">
              <i class="pi pi-check-circle text-green-300 text-xl"></i>
            </div>
            <p class="text-base font-regular">Task Tracking</p>
          </div>
          <div class="flex flex-col items-center justify-center gap-2">
            <div class="bg-beige-pastel p-2  rounded-md w-fit px-4 py-3">
              <i class="pi pi-users text-green-300 text-xl"></i>
            </div>
            <p class="text-base font-regular">Family Sync</p>
          </div>
          <div class="flex flex-col items-center justify-center gap-2">
            <div class="bg-beige-pastel p-2  rounded-md w-fit px-4 py-3">
              <i class="pi pi-chart-bar text-green-300 text-xl"></i>
            </div>
            <p class="text-base font-regular">Progress</p>
          </div>
        </div>
      </div>
      <InscriptionForm :dataRegister="RegisterFormRef" :errorRegister="RegisterFormError" :toggleDisplay="toggleDisplay" />
    </div> 
  </div>
</template>
