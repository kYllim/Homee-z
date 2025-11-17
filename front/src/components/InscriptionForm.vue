<script setup lang="ts"></script>

<template>
    <!-- Inscription -->
    <div class="flex flex-col gap-5">
    <h1 class="text-[25px] font-bold">Inscription</h1>
    <input
        class="border-solid border-2 border-black px-5 py-3 rounded-[20px]"
        name="name"
        type="text"
        placeholder="Nom"
    />
    <input
        class="border-solid border-2 border-black px-5 py-3 rounded-[20px]"
        name="firstName"
        type="text"
        placeholder="Prénom"
    />
    <input
        class="border-solid border-2 border-black px-5 py-3 rounded-[20px]"
        ame="emailRegister"
        type="email"
        placeholder="Email"
    />
    <input
        class="border-solid border-2 border-black px-5 py-3 rounded-[20px]"
        name="passwordRegister"
        type="password"
        placeholder="Mot de passe"
    />
    <input
        class="border-solid border-2 border-black px-5 py-3 rounded-[20px]"
        name="passwordComfirmation"
        type="password"
        placeholder="Comfirmer votre mot de passe"
    />
    </div>
    
</template>