async (page) => page.locator('.site-nav .nav-submenu-list a').first().evaluate(el => {
 const matches=[]; const inspect=rules=>{for(const rule of rules){if(rule.selectorText && rule.style?.display){try{if(el.matches(rule.selectorText))matches.push({selector:rule.selectorText,display:rule.style.display,priority:rule.style.getPropertyPriority('display')});}catch{}}if(rule.cssRules)inspect(rule.cssRules);}}; for(const sheet of document.styleSheets){try{inspect(sheet.cssRules)}catch{}} return matches;
})
