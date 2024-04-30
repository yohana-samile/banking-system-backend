<html>
<head>
    <title>Accounts</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <h1>Accounts</h1>
        <ul id="accountList">
            <!-- Accounts will be dynamically added here -->
        </ul>
        <div id="accountsContainer"></div>

        <script>
            // Fetch data from the API endpoint
            fetch('/api/accounts')
                .then(response => response.json())
                .then(data => {
                    // Get the container element
                    const accountsContainer = document.getElementById('accountsContainer');

                    // Create HTML table to display accounts
                    const table = document.createElement('table');
                    table.innerHTML = `
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Account Type</th>
                        </tr>
                    `;

                    // Loop through the data and add rows to the table
                    data.forEach(account => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${account.id}</td>
                            <td>${account.name}</td>
                            <td>${account.account_type}</td>
                        `;
                        table.appendChild(row);
                    });

                    // Append the table to the container
                    accountsContainer.appendChild(table);
                })
                .catch(error => console.error('Error fetching accounts:', error));
        </script>

</body>
</html>
