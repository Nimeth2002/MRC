import sqlite3

# Connect to the database
conn = sqlite3.connect('results.db')

# Create a cursor object
cur = conn.cursor()

# Execute a SELECT statement
cur.execute('SELECT * FROM results')

# Fetch the results and print them
results = cur.fetchall()
for row in results:
    print(row)

# Close the cursor and the connection
cur.close()
conn.close()
