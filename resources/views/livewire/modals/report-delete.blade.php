<div class="p-4">
    <div class="my-4 text-5xl text-center text-red-400">
        <i class="fas fa-exclamation-circle fa-lg"></i>
    </div>
    <div class="text-lg text-center font-bold">
        Are you sure you want to delete this report?
    </div>

    <table class="mt-4 border-collapse w-full border border-slate-400 dark:border-slate-500 bg-white dark:bg-slate-800 text-sm shadow-sm">
        <tbody>
          <tr>
            <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">Character name</td>
            <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">{{ $report->character_name }}</td>
          </tr>
          <tr>
            <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">Date/time</td>
            <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">{{ date('Y-m-d H:i', $report->unix_to) }}</td>
          </tr>
          <tr>
            <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">Description</td>
            <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">{{ Str::limit($report->description, 50) }}</td>
          </tr>
        </tbody>
      </table>

      <div class="mt-4 grid grid-cols-2 gap-4 text-center">
            <div class="">
                <button wire:click="deleteReport()" type="button" class="py-2 px-4 rounded-lg bg-red-400">Delete</button>
            </div>
            <div class="">
                <button wire:click="$emit('closeModal')" type="button" class="py-2 px-4 rounded-lg bg-gray-200 border border-gray-400">Cancel</button>
            </div>
      </div>
</div>
