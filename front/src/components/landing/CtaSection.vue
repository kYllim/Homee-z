<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  },
  buttonText: {
    type: String,
    required: true
  },
  variant: {
    type: String,
    default: 'solid' // 'solid' ou 'gradient'
  }
})
</script>

<template>
  <section
    :class="[
      'py-12 md:py-20',
      variant === 'gradient'
        ? 'bg-linear-to-r from-green_pastel to-lighBlue'
        : 'bg-green_pastel'
    ]"
  >
    <div class="max-w-5xl mx-auto px-4 md:px-6 text-center">
      <h3 class="text-2xl sm:text-3xl md:text-4xl font-['Outfit'] font-bold text-white mb-4 md:mb-6">
        {{ title }}
      </h3>
      <p class="text-base sm:text-lg md:text-xl text-white opacity-90 mb-6 md:mb-8">
        {{ description }}
      </p>
      <button class="px-8 md:px-10 py-3 md:py-4 bg-white text-green_pastel rounded-xl hover:bg-opacity-90 transition font-semibold text-base md:text-lg shadow-lg">
        {{ buttonText }}
      </button>
    </div>
  </section>
</template>