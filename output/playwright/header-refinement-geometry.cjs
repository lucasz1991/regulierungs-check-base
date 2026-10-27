async (page) => {
 const results=[];
 for(const width of [1180,1200,1237,1280,1440]) {
  await page.setViewportSize({width,height:850});
  results.push(await page.locator('[data-homepage-header]').evaluate(el=>{
   const box=e=>{const r=e.getBoundingClientRect();return {x:r.x,right:r.right,width:r.width,height:r.height}};
   const nav=el.querySelector('.site-nav');
   const baseline={viewport:innerWidth,header:box(el),brand:box(el.querySelector('.brand')),nav:box(nav),tools:box(el.querySelector('.header-tools')),navVisible:getComputedStyle(nav).display,overflow:document.documentElement.scrollWidth>innerWidth};
   const extra=nav.querySelector(':scope>a').cloneNode(true); extra.querySelector('.nav-label').textContent='Blog'; extra.removeAttribute('aria-current'); nav.insertBefore(extra,nav.querySelector('.mobile-nav-utilities'));
   baseline.withBlog={nav:box(nav),last:box(extra),tools:box(el.querySelector('.header-tools')),overflow:document.documentElement.scrollWidth>innerWidth};
   extra.remove(); return baseline;
  }));
 }
 return results;
}
