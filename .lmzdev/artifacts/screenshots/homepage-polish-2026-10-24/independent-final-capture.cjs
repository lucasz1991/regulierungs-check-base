async (page) => {
  const out='.lmzdev/artifacts/screenshots/homepage-polish-2026-10-24';
  await page.emulateMedia({reducedMotion:'reduce'});
  await page.goto('http://127.0.0.1:8186/');
  const light=page.getByRole('button',{name:'Zum hellen Modus wechseln',exact:true});
  if(await light.count())await light.click();
  for(const width of [1237,1440,980,1024,768,390,320]){
    await page.setViewportSize({width,height:1018});
    await page.goto('http://127.0.0.1:8186/');
    await page.evaluate(()=>document.fonts.ready);
    await page.screenshot({path:`${out}/independent-hero-${width}.png`,animations:'disabled'});
    if([768,390,320].includes(width))await page.locator('#ablauf').screenshot({path:`${out}/independent-process-${width}.png`,animations:'disabled'});
    if(width===320){await page.locator('#plattform').screenshot({path:`${out}/independent-about-320.png`,animations:'disabled'});await page.locator('#newsletter').screenshot({path:`${out}/independent-newsletter-320.png`,animations:'disabled'});}
  }
  await page.setViewportSize({width:390,height:1018});
  await page.goto('http://127.0.0.1:8186/');
  await page.getByRole('button',{name:'Zum dunklen Modus wechseln',exact:true}).click();
  await page.waitForTimeout(500);
  await page.screenshot({path:`${out}/independent-dark-390.png`,animations:'disabled'});
  await page.locator('#newsletter').screenshot({path:`${out}/independent-newsletter-dark-390.png`,animations:'disabled'});
  return {screenshots:'14 stable light/dark screenshots captured with reduced motion',location:out};
}
