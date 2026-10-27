async (page) => {
 const errors=[]; const listener=err=>errors.push(err.message); page.on('pageerror',listener);
 await page.getByRole('button',{name:'Suche öffnen'}).click();
 await page.getByRole('dialog',{name:'Suche',exact:true}).getByRole('button',{name:'Alles',exact:true}).click();
 await page.getByRole('button',{name:'Versicherungen',exact:true}).click();
 await page.getByRole('button',{name:'Versicher.',exact:true}).waitFor({state:'visible'});
 await page.locator('#search-dropdown').fill('HUK');
 await page.locator('[id="Search-menü"]').getByText('HUK-Coburg', {exact:true}).waitFor({state:'visible'});
 if(!(await page.locator('#search-dropdown').evaluate(el=>el===document.activeElement)))throw new Error('Search focus lost after Livewire morph');
 await page.getByRole('button',{name:'Suche schließen',exact:true}).click();
 await page.locator('.site-nav a').filter({hasText:'News'}).click();
 await page.waitForURL('**/news');
 if(await page.locator('[data-homepage-header]').count())throw new Error('Home header leaked to native News');
 const nativeHome = page.getByRole('link',{name:'Home',exact:true}).first();
 await nativeHome.click();
 await page.waitForURL('http://127.0.0.1:6666/');
 await page.locator('[data-homepage-header]').waitFor();
 await page.setViewportSize({width:360,height:800});
 await page.getByRole('button',{name:'Hauptmenü öffnen'}).click();
 if(await page.locator('#site-nav').getAttribute('aria-hidden')!=='false')throw new Error('Menu not restored after navigate');
 await page.keyboard.press('Escape');
 await page.getByRole('button',{name:'Suche öffnen'}).click();
 await page.locator('#search-dropdown').fill('Allianz');
 await page.locator('[id="Search-menü"]').getByText('Allianz', {exact:true}).waitFor({state:'visible'});
 await page.keyboard.press('Escape');
 page.off('pageerror',listener);
 if(errors.length)throw new Error(errors.join('; '));
 return {nativeSearchFilter:true,focusAfterMorph:true,homeNativeHomeNavigation:true,mobileMenuAfterNavigate:true,searchAfterNavigate:true,pageErrors:errors};
}

