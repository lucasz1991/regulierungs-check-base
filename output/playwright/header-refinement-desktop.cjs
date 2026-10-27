async (page) => {
 const checks=[];
 const errors=[]; const onError=err=>errors.push(err.message); page.on('pageerror',onError);
 for(const width of [1180,1200,1237,1280]) {
  await page.setViewportSize({width,height:850});
  const state=await page.locator('#site-nav').evaluate(el=>({inert:el.inert,aria:el.getAttribute('aria-hidden'),display:getComputedStyle(el).display,overflow:document.documentElement.scrollWidth>innerWidth}));
  if(state.inert||state.aria||state.display!=='flex'||state.overflow)throw new Error(JSON.stringify({width,...state}));
  if(await page.getByRole('button',{name:'Hauptmenü öffnen'}).count())throw new Error('Desktop burger exposed '+width);
  await page.locator('#site-nav summary').filter({hasText:'Leistungs-CHECK'}).focus();
  await page.keyboard.press('ArrowDown');
  await page.getByRole('link',{name:'Versicherungs Anbieter',exact:true}).waitFor({state:'visible'});
  if(!(await page.getByRole('link',{name:'Versicherungs Anbieter',exact:true}).evaluate(el=>el===document.activeElement)))throw new Error('Dropdown focus '+width);
  await page.keyboard.press('Escape');
  checks.push('Desktop navigation and keyboard '+width);
 }
 await page.setViewportSize({width:1237,height:850});
 await page.getByRole('button',{name:'Suche öffnen'}).click();
 await page.locator('#search-dropdown').fill('Allianz');
 await page.locator('[id="Search-menü"]').getByText('Allianz',{exact:true}).waitFor({state:'visible'});
 await page.keyboard.press('Escape');
 await page.locator('.header-account summary').click();
 await page.locator('.header-account').getByRole('link',{name:'Anmelden',exact:true}).waitFor({state:'visible'});
 await page.keyboard.press('Escape');
 checks.push('Native search and guest account 1237');
 await page.locator('.brand').focus();
 await page.screenshot({path:'output/playwright/header-refinement-after-1237.png'});
 await page.locator('[data-homepage-header]').screenshot({path:'output/playwright/header-refinement-desktop.png'});
 await page.setViewportSize({width:1179,height:850});
 await page.getByRole('button',{name:'Hauptmenü öffnen'}).click();
 await page.setViewportSize({width:1180,height:850});
 await page.locator('#site-nav').getByRole('link',{name:'Home',exact:true}).waitFor({state:'visible'});
 const crossed=await page.locator('#site-nav').evaluate(el=>({inert:el.inert,open:el.classList.contains('open'),aria:el.getAttribute('aria-hidden'),locked:document.documentElement.style.overflow}));
 if(crossed.inert||crossed.open||crossed.aria||crossed.locked==='hidden')throw new Error(JSON.stringify(crossed));
 checks.push('Mobile open menu resets cleanly at desktop breakpoint');
 page.off('pageerror',onError);
 if(errors.length)throw new Error(errors.join('; '));
 return checks;
}
