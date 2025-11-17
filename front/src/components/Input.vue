<script setup lang="ts">
    import { computed, ref } from 'vue';

    interface Iprops {
        type : 'checkbox' | 'text' | 'email' | 'password' | 'number' | 'tel' | 'url' | 'date',
        name? : string,
        placeholder? : string,
        data : string ,
        errorMessage? : string,
        title : string,
        icon : "pi-lock" | "pi-at" 
    }

    const props = defineProps<Iprops>()

    const emit = defineEmits<{
        (e : 'update:data', value: string) : void
    }>();

    const handleInput = (event: Event) => {
        const target = event.target as HTMLInputElement;
        emit('update:data', target.value);
    }

    const CurrentType = ref(props.type);
    const ShowPassword = ref(false);

    const togglePasswordVisibility = () => {
        ShowPassword.value = !ShowPassword.value;
        CurrentType.value = ShowPassword.value ? 'text' : 'password';
    };
    
</script>

<template>
    <label>
        <h4 class="text-lg font-semibold mb-1">{{ props.title }}</h4>
        <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl w-full"
            :class="props.errorMessage ? 'border-red-500' : 'border-gray-200'"
        >
            <i :class="['pi',props.icon, 'text-gray-700']"></i>
            <input :type="CurrentType" :name="props.name" :placeholder="props.placeholder" v-model="props.data"
                class="bg-transparent outline-none flex-1 text-gray-700 placeholder-gray-400"
                @input="handleInput"
            />
            <i v-if="props.type === 'password'" :class="['pi',ShowPassword ? 'pi-eye-slash' : 'pi-eye', 'text-gray-700']" @click="togglePasswordVisibility"></i>
        </div>
        <span v-if="!!props.errorMessage" class="text-red-500 font-bold mt-1">{{ props.errorMessage }}</span>
    </label>
</template>