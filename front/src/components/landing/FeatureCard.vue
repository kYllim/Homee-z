<script setup>
defineProps({
  icon: {
    type: String,
    required: true
  },
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  }
})
</script>

<template>
  <div class="bg-white rounded-xl md:rounded-2xl p-5 md:p-8 shadow-md hover:shadow-xl transition">
    <div class="w-12 h-12 md:w-16 md:h-16 bg-[#FAFAFA] bg-opacity-10 rounded-lg md:rounded-xl flex items-center justify-center mb-4 md:mb-6">
      <i :class="icon" class="text-green_pastel text-2xl md:text-3xl"></i>
    </div>
    <h4 class="text-lg md:text-xl font-['Outfit'] font-semibold text-[#333333] mb-2 md:mb-3">
      {{ title }}
    </h4>
    <p class="text-sm md:text-base text-lighBlue">
      {{ description }}
    </p>
  </div>
</template>
