<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <div class="container mx-auto my-4 px-4 flex flex-wrap gap-4">
    <div class="w-full">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-50">
            Last 30 days
        </h3>

        <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="item in stats" :key="item.id" class="relative bg-white dark:bg-slate-600 pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden">
                <dt>
                    <div class="absolute bg-indigo-500 rounded-md p-3">
                        <component :is="item.icon" class="h-6 w-6 text-white" aria-hidden="true" />
                    </div>
                    <p class="ml-16 text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ item.name }}</p>
                </dt>
                <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-50">
                        {{ item.stat }}
                    </p>
                    <p :class="[item.changeType === 'increase' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400', 'ml-2 flex items-baseline text-sm font-semibold']">
                        <ArrowSmUpIcon v-if="item.changeType === 'increase'" class="self-center flex-shrink-0 h-5 w-5 text-green-500" aria-hidden="true" />
                        <ArrowSmDownIcon v-else class="self-center flex-shrink-0 h-5 w-5 text-red-500" aria-hidden="true" />
                        <span class="sr-only"> {{ item.changeType === 'increase' ? 'Increased' : 'Decreased' }} by </span>
                        {{ item.change }}
                    </p>
                    <div class="absolute bottom-0 inset-x-0 bg-gray-50 dark:bg-slate-800 px-4 py-4 sm:px-6">
                        <div class="text-sm">
                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                            View all<span class="sr-only"> {{ item.name }} stats</span></a
                        >
                        </div>
                    </div>
                </dd>
            </div>
        </dl>
    </div>

    <div class="flex-1">
        <div class="my-2 text-xl font-medium flex items-center justify-between">
            <div>History</div>
            <div>
                <button @click.prevent="$store.dispatch('fetchPlantSeedLogs')" role="refresh" class="mt-2">
                    <RefreshIcon class="h-6 w-6 text-gray-500" aria-hidden="true" />
                </button>
            </div>
        </div>
        <ul role="list" class="my-4 p-4 bg-white dark:bg-slate-600 rounded-lg shadow -mb-8 max-h-64 overflow-y-scroll overflow-x-hidden">
            <li v-for="(event, eventIdx) in $store.getters.activityLogs" :key="event.id">
                <div class="relative" :class="[eventIdx === $store.getters.activityLogs.length - 1 ?'pb-0' :'pb-8']">
                    <span v-if="(eventIdx !== $store.getters.activityLogs.length - 1)" class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true" />
                    <div class="relative flex space-x-3">
                        <div>
                        <span :class="[event.iconBackground ?? 'bg-blue-500', 'h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-gray-600']">
                            <check-icon class="h-5 w-5 text-slate-900 dark:text-slate-100" aria-hidden="true" />
                        </span>
                        </div>

                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-slate-400">
                            {{ event.description }} <router-link :to="'/greenhouse/plants'" class="font-medium text-gray-900 dark:text-slate-50">{{ event?.subject?.name }}</router-link>
                            </p>
                        </div>
                        <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-slate-400">
                            <time :datetime="event.created_at">{{ event.created_at }}</time>
                        </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="flex-1 w-1/2">
        <div class="my-4 text-xl font-medium">
            Current Crop
        </div>

        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700 my-4 bg-white dark:bg-slate-600 rounded-lg shadow px-4 max-h-64 overflow-y-scroll">
            <li v-for="seed in $store.getters.seeds" :key="seed.id" class="py-4 flex">
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-50">{{ seed.name }}</p>
                     <p v-if="seed.geminated_at" class="text-sm text-gray-500 dark:text-gray-400">Germinated {{ dayjs(seed.germinated_at).from(dayjs()) }}</p>
                    <p v-if="seed.planted_at" class="text-sm text-gray-500 dark:text-gray-400">Planted {{ dayjs().from(dayjs(seed.planted_at)) }}</p>
                    <p v-if="seed.last_fed_at" class="text-sm text-gray-500 dark:text-gray-400">Last fed {{ dayjs(seed.last_fed_at).from(dayjs()) }}</p>
                    <p v-if="seed.last_watered_at" class="text-sm text-gray-500 dark:text-gray-400">Last watered {{ dayjs(seed.last_watered_at).from(dayjs()) }}</p>
                   <p v-if="seed.expected_harvest_date" class="text-sm text-gray-500 dark:text-gray-400">Harvest {{ dayjs(seed.expected_harvest_date).from(dayjs()) }}</p>
                </div>
            </li>
        </ul>
    </div> 
    <div class="w-full mt-8">
        <footer class="bg-white dark:bg-slate-600 p-4 rounded shadow">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    Footer &copy; 2021
                </div>
            </div>
        </footer>
    </div>
</div>
</template>

<script>
import { ArrowSmDownIcon, ArrowSmUpIcon, RefreshIcon } from '@heroicons/vue/solid'
import { CursorClickIcon, MailOpenIcon, UsersIcon } from '@heroicons/vue/outline'
import { CheckIcon, ThumbUpIcon, UserIcon } from '@heroicons/vue/solid'

const stats = [
  { id: 1, name: 'Day Water Consumption', stat: '797 mL', icon: UsersIcon, change: '122', changeType: 'increase' },
  { id: 2, name: 'Day Energy Consumption', stat: '581.8 kW', icon: MailOpenIcon, change: '5.4%', changeType: 'increase' },
  { id: 3, name: 'Day Length', stat: '12.04 hrs', icon: CursorClickIcon, change: '3.2%', changeType: 'decrease' },
]

export default {
  components: {
    ArrowSmDownIcon,
    ArrowSmUpIcon,
    CheckIcon, ThumbUpIcon, UserIcon,
    RefreshIcon
  },
  setup() {
    return {
      stats,
      dayjs,
    }
  },
  mounted() {
    this.$store.dispatch('fetchPlantSeedLogs')
  }
}
</script>