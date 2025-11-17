<script setup lang="ts">
import { ref } from "vue";
import type { Ref } from "vue";

const IsOpen: Ref<boolean> = ref(false);
</script>

<template>
  <div class="absolute bottom-0 left-0">
    <nav
      class="flex flex-row justify-center content-center py-2 px-4 relative lg:justify-between lg:px-[58px] lg:py-6"
    >
      <button @click="IsOpen = !IsOpen" class="absolute top-4 left-4 lg:hidden">
        Menu
      </button>
      <h1 class="font-bold text-[22px] lg:text-[50px]">homee'z</h1>
      <!-- Naviation Desktop -->
      <div
        class="hidden lg:flex flex-row gap-[22px] justify-center content-center items-center"
      >
        <a href="#" class="text-[22px]">se connecter</a>
        <a
          href="#"
          class="text-[22px] bg-green-test rounded-[20px] px-[20px] py-[15px]"
          >créer un compte</a
        >
      </div>
    </nav>
    <!-- Naviation mobile -->
    <div
      :class="IsOpen ? 'flex flex-col border-2 border-amber-400 px-2' : 'hidden'"
    >
      <a href="#">se connecter</a>
      <a href="#">créer un compte</a>
    </div>
  </div>
</template>
