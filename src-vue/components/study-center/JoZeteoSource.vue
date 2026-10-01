<template>
  <a
    :id="'cite-card-' + source.ref"
    :href="permalink"
    class="group shrink-0 snap-start flex flex-col w-[220px] rounded-2xl overflow-hidden bg-white text-gray-800 no-underline shadow-lg shadow-black/20 transition active:scale-[0.98]"
  >
    <div v-if="isRecording && source.thumbnail" class="relative aspect-video bg-gray-200">
      <img :src="source.thumbnail" alt="" loading="lazy" class="block w-full h-full object-cover">
      <span class="absolute top-2 left-2 flex items-center justify-center min-w-[20px] h-5 px-1 rounded-full bg-[#061375] text-white text-[11px] font-bold">
        {{ source.ref }}
      </span>
      <span v-if="timestampLabel" class="absolute bottom-2 right-2 px-1.5 rounded bg-black/75 text-white text-[11px] leading-[18px]">
        {{ timestampLabel }}
      </span>
    </div>

    <div class="flex-1 flex flex-col gap-1 p-3">
      <div v-if="!(isRecording && source.thumbnail)" class="flex items-center gap-2">
        <span class="flex items-center justify-center min-w-[20px] h-5 px-1 rounded-full bg-[#061375] text-white text-[11px] font-bold">
          {{ source.ref }}
        </span>
        <span v-if="source.source_type === 'manuscript'" class="text-[11px] font-semibold uppercase tracking-wide text-blue-600">Sendemanuskript</span>
      </div>
      <span class="text-sm font-semibold leading-snug line-clamp-2 group-hover:text-blue-700">{{ source.title }}</span>
      <span v-if="source.speaker || source.date" class="text-xs text-gray-500 line-clamp-1">
        {{ source.speaker }}<template v-if="source.speaker && source.date"> · </template>{{ source.date }}
      </span>
      <span v-if="source.excerpt" class="text-xs text-gray-500 line-clamp-2">{{ source.excerpt }}</span>
    </div>
  </a>
</template>

<script>
import JoStudyCenterCard from './JoStudyCenterCard.vue'

/** Reuses permalink/timestamp logic from JoStudyCenterCard. */
export default {
  extends: JoStudyCenterCard,
}
</script>
