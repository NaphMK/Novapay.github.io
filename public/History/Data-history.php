<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Transaction History</title>
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
      <h1 class="text-3xl font-bold mb-6 text-center">Data History</h1>

      <!-- Search and Entries Section -->
      <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
        <!-- Search Bar -->
        <div class="w-full sm:w-auto mb-4 sm:mb-0">
          <input
            type="text"
            id="search"
            placeholder="Search (e.g. mobile number)"
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
            <option value="50" selected>50</option>
            <option value="100">100</option>
          </select>
        </div>
      </div>

      <!-- Responsive Table -->
      <div class="overflow-x-auto shadow-md rounded-lg bg-blue-800">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-blue-700">
              <th class="px-4 py-2 border-b text-sm font-medium">Transaction ID</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Recipient Phone</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Mobile Network</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Amount</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Balance Before</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Balance After</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Status</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Date/Time</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Receipt</th>
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
          Showing 1 to 10 of 50 entries
        </p>
        <div class="mt-4 sm:mt-0 flex space-x-2">
          <button id="prev" class="px-3 py-1 border rounded-lg bg-blue-500 text-white hover:bg-blue-400">Prev</button>
          <button id="next" class="px-3 py-1 border rounded-lg bg-blue-500 text-white hover:bg-blue-400">Next</button>
        </div>
      </div>
    </div>
    <script>
  // Generate dummy table rows dynamically
  const totalRows = 50; // Total dummy rows
  let rows = [];
  let rowsPerPage = 10; // Default rows per page
  let currentPage = 1;
  let filteredRows = []; // To handle search results

  const generateRows = (numRows) => {
    const networks = ["MTN", "Airtel", "Etisalat", "Glo"];
    const statuses = ["Success", "Failed", "Pending"];
    let tableRows = [];

    for (let i = 1; i <= numRows; i++) {
      const phone = `080${Math.floor(Math.random() * 10000000)}`;
      tableRows.push({
        id: `TRX-${i.toString().padStart(4, "0")}`,
        phone: phone,
        network: networks[Math.floor(Math.random() * networks.length)],
        amount: `₦${Math.floor(Math.random() * 5000 + 500)}`,
        balanceBefore: `₦${Math.floor(Math.random() * 100000 + 5000)}`,
        balanceAfter: `₦${Math.floor(Math.random() * 100000 + 10000)}`,
        status: statuses[Math.floor(Math.random() * statuses.length)],
        dateTime: `${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}`,
      });
    }
    return tableRows;
  };

  const updateTable = (page = 1, perPage = 10) => {
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const rowsToDisplay = filteredRows.slice(start, end);

    const tableBody = rowsToDisplay
      .map(
        (row) => `
        <tr class="odd:bg-blue-800 even:bg-blue-700 hover:bg-blue-600">
          <td class="px-4 py-2 border-b">${row.id}</td>
          <td class="px-4 py-2 border-b">${row.phone}</td>
          <td class="px-4 py-2 border-b">${row.network}</td>
          <td class="px-4 py-2 border-b">${row.amount}</td>
          <td class="px-4 py-2 border-b">${row.balanceBefore}</td>
          <td class="px-4 py-2 border-b">${row.balanceAfter}</td>
          <td class="px-4 py-2 border-b">${row.status}</td>
          <td class="px-4 py-2 border-b">${row.dateTime}</td>
          <td class="px-4 py-2 border-b">
            <button class="px-2 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-400">View Receipt</button>
          </td>
        </tr>`
      )
      .join("");

    document.getElementById("table-body").innerHTML = tableBody;
    document.getElementById("pagination-info").innerText = `Showing ${start + 1} to ${Math.min(
      end,
      filteredRows.length
    )} of ${filteredRows.length} entries`;
  };

  const filterRows = (searchQuery) => {
    if (!searchQuery) {
      filteredRows = rows;
    } else {
      filteredRows = rows.filter((row) =>
        row.phone.toLowerCase().includes(searchQuery.toLowerCase())
      );
    }
    currentPage = 1; // Reset to the first page after filtering
    updateTable(currentPage, rowsPerPage);
  };

  document.addEventListener("DOMContentLoaded", () => {
    rows = generateRows(totalRows);
    filteredRows = [...rows]; // Initialize with all rows
    updateTable(currentPage, rowsPerPage);

    // Handle search
    document.getElementById("search").addEventListener("input", (e) => {
      const searchQuery = e.target.value;
      filterRows(searchQuery);
    });

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
      const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
      if (currentPage < totalPages) {
        currentPage++;
        updateTable(currentPage, rowsPerPage);
      }
    });
  });
</script>

  </body>
</html>
