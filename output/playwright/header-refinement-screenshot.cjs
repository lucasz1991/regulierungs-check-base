async (page) => {
 await page.setViewportSize({width:1237,height:850});
 await page.getByRole('dialog',{name:'Suche',exact:true}).waitFor({state:'hidden'});
 await page.evaluate(()=>{document.activeElement?.blur();window.scrollTo({top:0,behavior:'instant'});});
 await page.screenshot({path:'output/playwright/header-refinement-after-1237.png'});
 await page.locator('[data-homepage-header]').screenshot({path:'output/playwright/header-refinement-desktop.png'});
 return {desktopVisible:await page.getByRole('navigation',{name:'Hauptnavigation'}).isVisible(),searchClosed:await page.getByRole('dialog',{name:'Suche'}).count()===0};
}
