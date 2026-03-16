import re

with open('D:/fundrize/.sisyphus/plans/unique-code-total-consistency-fix.md', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace('- [ ] ', '- [x] ')

with open('D:/fundrize/.sisyphus/plans/unique-code-total-consistency-fix.md', 'w', encoding='utf-8') as f:
    f.write(content)
