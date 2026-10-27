async (page) => {
 await page.setViewportSize({width:1280,height:850});
 return await page.locator('[data-homepage-header]').evaluate(el=>({header:el.getBoundingClientRect().width,brand:el.querySelector('.brand').getBoundingClientRect().width,nav:el.querySelector('.site-nav').getBoundingClientRect().width,tools:el.querySelector('.header-tools').getBoundingClientRect().width,links:[...el.querySelector('.site-nav').children].map(e=>({text:e.textContent.trim().slice(0,35),width:e.getBoundingClientRect().width}))}));
}
