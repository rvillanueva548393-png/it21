import re

# Fix threats table description column
with open("resources/views/threats.blade.php", "r", encoding="utf-8") as f:
    threats = f.read()

threats = threats.replace('<td>{{ $threat->description }}</td>', '<td style="max-width: 250px; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; line-height: 1.4;">{{ $threat->description }}</td>')
threats = threats.replace('<th>Description</th>', '<th style="width: 30%;">Description</th>')

with open("resources/views/threats.blade.php", "w", encoding="utf-8") as f:
    f.write(threats)

# Fix overview recent threats description
with open("resources/views/overview.blade.php", "r", encoding="utf-8") as f:
    overview = f.read()

overview = overview.replace('margin-top: 5px;"', 'margin-top: 5px; word-wrap: break-word; overflow-wrap: anywhere;"')

with open("resources/views/overview.blade.php", "w", encoding="utf-8") as f:
    f.write(overview)

print("UI Fixed!")
