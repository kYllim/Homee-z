<script setup>
defineProps({
  question: {
    type: String,
    required: true
  },
  answer: {
    type: String,
    required: true
  }
})
</script>

<template>
  <div class="bg-white rounded-xl md:rounded-2xl p-5 md:p-8 shadow-md hover:shadow-lg transition">
    <h4 class="text-lg md:text-xl font-['Outfit'] font-semibold text-[#333333] mb-2 md:mb-3">
      {{ question }}
    </h4>
    <p class="text-sm md:text-base text-lighBlue">
      {{ answer }}
    </p>
  </div>
</template>
