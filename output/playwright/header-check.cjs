async (page) => {
 const checks = [];
 const check = (pass, name) => { if (!pass) throw new Error(name); checks.push(name); };
 await page.getByRole('button', {name:'Hauptmenü öffnen'}).click();
 check(await page.locator('#site-nav').getAttribute('aria-hidden') === 'false', 'Mobile menu opens');
 await page.locator('summary').filter({hasText:'Leistungs-CHECK'}).click();
 await page.getByRole('link',{name:'Versicherungs Anbieter',exact:true}).waitFor({state:'visible'});
 await page.screenshot({path:'output/playwright/header-mobile-menu.png'});
 await page.keyboard.press('Escape');
 check(await page.locator('#site-nav details[open]').count() === 0, 'Escape closes submenu');
 await page.keyboard.press('Escape');
 check(await page.getByRole('button',{name:'Hauptmenü öffnen'}).evaluate(el=>el===document.activeElement), 'Escape restores mobile menu focus');
 await page.getByRole('button',{name:'Suche öffnen'}).click();
 await page.getByRole('searchbox',{name:'Versicherungen und Informationen suchen'}).waitFor({state:'visible'});
 check(await page.locator('#search-dropdown').evaluate(el=>el===document.activeElement), 'Native search receives focus');
 await page.locator('#search-dropdown').fill('Allianz');
 await page.locator('[id="Search-menü"]').getByText('Allianz', {exact:true}).waitFor({state:'visible'});
 check(await page.locator('[id="Search-menü"]').getByText('Allianz',{exact:true}).count()>0, 'Native Livewire search returns real insurer');
 await page.screenshot({path:'output/playwright/header-mobile-search.png'});
 await page.keyboard.press('Escape');
 check(await page.getByRole('button',{name:'Suche öffnen'}).evaluate(el=>el===document.activeElement), 'Search Escape restores opener focus');
 await page.locator('.header-account summary').click();
 await page.locator('.header-account').getByRole('link',{name:'Anmelden',exact:true}).waitFor({state:'visible'});
 check(await page.locator('.header-account').getByRole('link',{name:'Registrieren',exact:true}).getAttribute('href') === 'http://127.0.0.1:6666/register','Guest account routes');
 await page.keyboard.press('Escape');
 await page.getByRole('button',{name:'Zum dunklen Modus wechseln',exact:true}).click();
 check(await page.locator('html').getAttribute('data-theme') === 'dark','Theme toggles dark');
 await page.getByRole('button',{name:'Zum hellen Modus wechseln',exact:true}).click();
 check(await page.locator('html').getAttribute('data-theme') === 'light','Theme toggles light');
 for (const width of [320,360,736,1024,1280]) {
  await page.setViewportSize({width,height:800});
  check(await page.evaluate(()=>document.documentElement.scrollWidth<=window.innerWidth), `No page overflow ${width}`);
  await page.locator('.site-footer').scrollIntoViewIfNeeded();
  check(await page.locator('.site-footer').evaluate(el=>el.scrollWidth<=window.innerWidth),`No footer overflow ${width}`);
  if(width===360) await page.screenshot({path:'output/playwright/footer-mobile.png'});
 }
 await page.evaluate(()=>window.scrollTo({top:0,behavior:'instant'}));
 await page.locator('summary').filter({hasText:'Leistungs-CHECK'}).focus();
 await page.keyboard.press('ArrowDown');
 check(await page.getByRole('link',{name:'Versicherungs Anbieter',exact:true}).evaluate(el=>el===document.activeElement),'Desktop arrow opens and focuses submenu');
 await page.keyboard.press('Escape');
 await page.screenshot({path:'output/playwright/header-desktop.png'});
 return checks;
}
