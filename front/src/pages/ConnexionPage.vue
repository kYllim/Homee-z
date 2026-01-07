<script setup lang="ts">
  import { ref,watch } from "vue";
  import type { Ref } from "vue";
  import { useRouter,useRoute } from "vue-router";
  import type { FormConnexion, ErrorsConexion, FormRegister, ErrorRegister } from "@/models/";
  import NavBarLanding from "@/components/Layout/NavBarLanding.vue";
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
    firstName : ref(""),
    name : ref("")
  };

  const RegisterFormError: ErrorRegister = {
    emailRegister : ref(""),
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
        <h2 class="font-regular text-5xl mb-2">Rejoignez Homeez</h2>
        <p class="font-regular text-lg text-lighBlue">Gérez vos tâches ménagères efficacement et ne manquez plus jamais une corvée.</p>
        <div class="flex flex-col gap-4 mt-4">
          <!-- une feature -->
          <div class="flex flex-row gap-2">
            <div class="bg-green_pastel p-2 flex items-center justify-center rounded-md">
              <i class="pi pi-list-check text-green-900" style="font-size: 2rem"></i>
            </div>
            <div class="flex flex-col ">
              <h3 class="text-lg font-semibold">Gestion des tâches</h3>
              <p class="text-lighBlue text-md">Créez, assignez et suivez vos corvées ménagères facilement</p>
            </div>
          </div>
          <!-- une feature -->
          <div class="flex flex-row gap-2">
            <div class="bg-beige-pastel p-2 flex items-center justify-center rounded-md">
              <i class="pi pi-users text-black" style="font-size: 2rem"></i>
            </div>
            <div class="flex flex-col ">
              <h3 class="text-lg font-semibold">Collaboration familiale</h3>
              <p class="text-lighBlue text-md">Partagez les tâches avec les membres de votre famille et restez organisés ensemble</p>
            </div>
          </div>
          <!-- une feature -->
          <div class="flex flex-row gap-2">
            <div class="bg-lighBlue p-2 flex items-center justify-center rounded-md">
              <i class="pi pi-bell text-gray-300" style="font-size: 2rem"></i>
            </div>
            <div class="flex flex-col ">
              <h3 class="text-lg font-semibold">Rappels intelligents</h3>
              <p class="text-lighBlue text-md">Recevez des notifications pour les tâches à venir et en retard</p>
            </div>
          </div>
        </div>
      </div>
      <ConnexionForm  :dataConnexion="ConnexionFormRef" :errorConnexion="ConnexionFormError" :toggleDisplay="toggleDisplay"/>
    </div> 
    <div v-else class="flex flex-row items-baseline justify-center w-full mt-6">
      <div class="hidden lg:flex flex-col p-4 ml-2 justify-start pl-28">
        <h2 class="font-regular text-5xl">Organisez votre maison,</h2>
        <h2 class="font-regular text-5xl text-green_pastel mb-4">Simplifiez votre vie</h2>
        <p class="font-regular text-lg text-lighBlue mb-4">
          Rejoignez des milliers de familles qui ont transformé la gestion de leur foyer avec Homeez. Suivez les tâches, assignez les responsabilités et maintenez un environnement domestique harmonieux.
        </p>
        <div class="flex flex-row gap-3">
          <div class="flex flex-col items-center justify-center gap-2">
            <div class="bg-beige-pastel p-2  rounded-md w-fit px-4 py-3">
              <i class="pi pi-check-circle text-green-300 text-xl"></i>
            </div>
            <p class="text-base font-regular">Suivi des tâches</p>
          </div>
          <div class="flex flex-col items-center justify-center gap-2">
            <div class="bg-beige-pastel p-2  rounded-md w-fit px-4 py-3">
              <i class="pi pi-users text-green-300 text-xl"></i>
            </div>
            <p class="text-base font-regular">Synchronisation familiale</p>
          </div>
          <div class="flex flex-col items-center justify-center gap-2">
            <div class="bg-beige-pastel p-2  rounded-md w-fit px-4 py-3">
              <i class="pi pi-chart-bar text-green-300 text-xl"></i>
            </div>
            <p class="text-base font-regular">Progression</p>
          </div>
        </div>
      </div>
      <InscriptionForm :dataRegister="RegisterFormRef" :errorRegister="RegisterFormError" :toggleDisplay="toggleDisplay" />
    </div> 
  </div>
</template>
