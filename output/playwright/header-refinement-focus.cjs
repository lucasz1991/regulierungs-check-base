async (page) => {
 await page.reload();
 await page.setViewportSize({width:1237,height:850});
 await page.locator('#site-nav').getByRole('link',{name:'Home',exact:true}).focus();
 await page.setViewportSize({width:1179,height:850});
 await page.waitForFunction(()=>document.activeElement===document.querySelector('.menu-toggle'));
 const mobile=await page.locator('#site-nav').evaluate(el=>({inert:el.inert,aria:el.getAttribute('aria-hidden')}));
 if(!mobile.inert||mobile.aria!=='true')throw new Error(JSON.stringify(mobile));
 await page.setViewportSize({width:1180,height:850});
 await page.waitForFunction(()=>document.activeElement===document.querySelector('.site-header .brand'));
 const desktop=await page.locator('#site-nav').evaluate(el=>({inert:el.inert,aria:el.getAttribute('aria-hidden'),display:getComputedStyle(el).display}));
 if(desktop.inert||desktop.aria||desktop.display!=='flex')throw new Error(JSON.stringify(desktop));
 await page.setViewportSize({width:1237,height:850});
 await page.evaluate(()=>document.activeElement.blur());
 await page.screenshot({path:'output/playwright/header-refinement-after-1237.png'});
 return {desktopToMobileFocus:'menu-toggle',mobileToDesktopFocus:'brand',closedMobileMenuInert:mobile.inert,desktopNavigationVisible:desktop.display==='flex',overflow:await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth)};
}
