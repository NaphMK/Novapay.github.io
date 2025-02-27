<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fund History</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      /* Custom scrollbar for overflow areas */
      ::-webkit-scrollbar {
        width: 8px;
      }
      ::-webkit-scrollbar-thumb {
        background-color: #60a5fa;
        border-radius: 8px;
      }
      ::-webkit-scrollbar-thumb:hover {
        background-color: #3b82f6;
      }
    </style>
  </head>
  <body class="bg-blue-900 text-white font-sans">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Page Header -->
      <h1 class="text-3xl font-bold mb-6 text-center">Fund History</h1>

      <!-- Search and Entries Section -->
      <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
        <!-- Search Bar -->
        <div class="w-full sm:w-auto mb-4 sm:mb-0">
          <input
            type="text"
            id="search"
            placeholder="Search (e.g. transaction ID)"
            class="w-full sm:w-72 px-4 py-2 border rounded-lg bg-blue-700 text-white placeholder-gray-300 focus:ring focus:ring-blue-500"
          />
        </div>

        <!-- Entries Dropdown -->
        <div>
          <label for="entries" class="mr-2 text-gray-300">Show</label>
          <select
            id="entries"
            class="px-4 py-2 border rounded-lg bg-blue-700 text-white focus:ring focus:ring-blue-500"
          >
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
        </div>
      </div>

      <!-- Responsive Table -->
      <div class="overflow-x-auto shadow-md rounded-lg bg-blue-800">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-blue-700">
                <th class="px-4 py-2 border-b text-sm font-medium">Username</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Transaction ID</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Amount</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Earn</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Gateway Settlement</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Method</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Date/Time</th>
            </tr>
          </thead>
          <tbody id="table-body">
            <!-- Data will be populated here dynamically -->
          </tbody>
        </table>
      </div>

      <!-- Pagination Section -->
      <div class="mt-4 flex flex-col sm:flex-row justify-between items-center">
        <p id="pagination-info" class="text-gray-300">
          Showing 1 to 2 of 2 entries
        </p>
        <div class="mt-4 sm:mt-0 flex space-x-2">
          <button id="prev" class="px-3 py-1 border rounded-lg bg-blue-500 text-white hover:bg-blue-400">Prev</button>
          <button id="next" class="px-3 py-1 border rounded-lg bg-blue-500 text-white hover:bg-blue-400">Next</button>
        </div>
      </div>
    </div>

    <script>
      // Generate dummy table rows dynamically
      const totalRows = 2; // Total dummy rows (only 2 entries)
      let rows = [];
      let rowsPerPage = 10; // Default rows per page (10 entries)
      let currentPage = 1;
      const methods = ["Bank Transfer", "Mobile Wallet", "Cash", "Online Payment"];

      // Generate 2 rows of dummy data for fund history
      const generateRows = (numRows) => {
        let tableRows = [];
        for (let i = 1; i <= numRows; i++) {
          tableRows.push(`
            <tr class="odd:bg-blue-800 even:bg-blue-700 hover:bg-blue-600">
              <td class="px-4 py-2 border-b">TRX-${i.toString().padStart(4, "0")}</td>
              <td class="px-4 py-2 border-b">₦${Math.floor(Math.random() * 10000 + 500)}</td>
              <td class="px-4 py-2 border-b">${methods[Math.floor(Math.random() * methods.length)]}</td>
              <td class="px-4 py-2 border-b">${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}</td>
            </tr>
          `);
        }
        return tableRows;
      };

      const updateTable = (page = 1, perPage = 10) => {
        const start = (page - 1) * perPage;
        const end = start + perPage;
        const rowsToDisplay = rows.slice(start, end);

        document.getElementById("table-body").innerHTML = rowsToDisplay.join('');
        document.getElementById("pagination-info").innerText = `Showing ${start + 1} to ${Math.min(end, totalRows)} of ${totalRows} entries`;
      };

      document.addEventListener("DOMContentLoaded", () => {
        rows = generateRows(totalRows);
        updateTable(currentPage, rowsPerPage);

        // Handle entries per page change
        document.getElementById("entries").addEventListener("change", (e) => {
          rowsPerPage = parseInt(e.target.value, 10);
          currentPage = 1; // Reset to first page on change
          updateTable(currentPage, rowsPerPage);
        });

        // Handle pagination
        document.getElementById("prev").addEventListener("click", () => {
          if (currentPage > 1) {
            currentPage--;
            updateTable(currentPage, rowsPerPage);
          }
        });

        document.getElementById("next").addEventListener("click", () => {
          const totalPages = Math.ceil(totalRows / rowsPerPage);
          if (currentPage < totalPages) {
            currentPage++;
            updateTable(currentPage, rowsPerPage);
          }
        });
      });
    </script>
  </body>
</html>
