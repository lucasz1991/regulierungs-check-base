async (page) => {
  await page.setViewportSize({width:390,height:1018});
  await page.emulateMedia({reducedMotion:'reduce'});
  await page.goto('http://127.0.0.1:8186/');
  await page.evaluate(()=>document.fonts.ready);
  const textMetrics=await page.evaluate(()=>{
    const selectors=['#ablauf h3','#ablauf p','#plattform p','#plattform h3','#newsletter p','footer a','footer p'];
    return selectors.map(s=>({selector:s,items:[...document.querySelectorAll(s)].slice(0,4).map(e=>{const c=getComputedStyle(e);return {text:e.textContent.trim().slice(0,55),font:c.fontSize,weight:c.fontWeight,line:c.lineHeight,color:c.color,display:c.display,background:c.backgroundColor};})}));
  });
  const tabs=[];
  for(let n=0;n<8;n++) {
    await page.keyboard.press('Tab');
    tabs.push(await page.evaluate(()=>{const e=document.activeElement,c=getComputedStyle(e);return {text:(e.textContent||e.getAttribute('aria-label')).trim().slice(0,60),tag:e.tagName,outline:c.outline,shadow:c.boxShadow,rect:{y:e.getBoundingClientRect().top,h:e.getBoundingClientRect().height}};}));
  }
  await page.getByRole('button',{name:'Erfahrung teilen',exact:true}).click();
  await page.waitForTimeout(300);
  const ratingOpen=await page.getByRole('button',{name:'Abbrechen',exact:true}).isVisible();
  const focusWhenOpen=await page.evaluate(()=>({name:document.activeElement?.getAttribute('aria-label'),text:document.activeElement?.textContent?.trim().slice(0,50),tag:document.activeElement?.tagName}));
  await page.keyboard.press('Escape');
  await page.waitForTimeout(250);
  const ratingStillOpenAfterEscape=await page.getByRole('button',{name:'Abbrechen',exact:true}).isVisible();
  if(ratingStillOpenAfterEscape)await page.getByRole('button',{name:'Abbrechen',exact:true}).click();
  await page.getByRole('heading',{name:'News & Ratgeber',exact:true}).scrollIntoViewIfNeeded();
  const newsBefore=await page.locator('[data-news-ticker] [data-carousel-track]').evaluate(e=>({scroll:e.scrollLeft,transform:getComputedStyle(e).transform}));
  await page.waitForTimeout(900);
  const newsAfter=await page.locator('[data-news-ticker] [data-carousel-track]').evaluate(e=>({scroll:e.scrollLeft,transform:getComputedStyle(e).transform}));
  const reduced=await page.locator('[data-news-toggle]').evaluate(e=>({label:e.getAttribute('aria-label'),pressed:e.getAttribute('aria-pressed'),text:e.textContent}));
  return {textMetrics,tabs,ratingOpen,focusWhenOpen,ratingStillOpenAfterEscape,reduced,newsBefore,newsAfter};
}
