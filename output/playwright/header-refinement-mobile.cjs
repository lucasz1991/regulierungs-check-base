async (page) => {
 const checks=[];
 await page.keyboard.press('Escape');
 await page.emulateMedia({reducedMotion:'reduce'});
 await page.getByRole('button',{name:'Hauptmenü öffnen'}).click();
 const motion=await page.locator('#site-nav').evaluate(el=>({transition:getComputedStyle(el).transitionDuration,animation:getComputedStyle(el.firstElementChild).animationDuration,transform:getComputedStyle(el).transform,inert:el.inert}));
 if(motion.transition!=='0s'||motion.animation!=='0s'||motion.transform!=='none'||motion.inert) throw new Error(JSON.stringify(motion));
 checks.push('Reduced Motion: no transitions or animations; menu interactive');
 await page.keyboard.press('Escape');
 if(!(await page.locator('#site-nav').evaluate(el=>el.inert)))throw new Error('Closed menu must be inert');
 if(!(await page.getByRole('button',{name:'Hauptmenü öffnen'}).evaluate(el=>el===document.activeElement)))throw new Error('Menu focus did not return');
 checks.push('Escape returns focus and closed mobile menu is inert');
 await page.emulateMedia({reducedMotion:'no-preference'});
 await page.getByRole('button',{name:'Suche öffnen'}).click();
 await page.locator('#search-dropdown').fill('Allianz');
 await page.locator('[id="Search-menü"]').getByText('Allianz',{exact:true}).waitFor({state:'visible'});
 await page.keyboard.press('Escape');
 checks.push('Native search still works and closes');
 for(const width of [320,360,736,1024,1179]){
  await page.setViewportSize({width,height:800});
  await page.getByRole('button',{name:'Hauptmenü öffnen'}).click();
  if(!(await page.locator('#site-nav').evaluate(el=>el.scrollWidth<=el.clientWidth+1))) throw new Error('Menu overflow '+width);
  if(!(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth))) throw new Error('Page overflow '+width);
  await page.keyboard.press('Escape');
  checks.push('Menu fits '+width);
 }
 return checks;
}
