async (page) => {
  const report = {url:page.url(), viewports:[]};
  const track = page.getByRole('list', {name:'Aktuelle News', exact:true});
  const heading = page.getByRole('heading', {name:'News & Ratgeber', exact:true});
  for (const width of [390,320]) {
    await page.setViewportSize({width,height:1018});
    await heading.scrollIntoViewIfNeeded();
    await page.mouse.move(0,0);
    await page.waitForTimeout(200);
    const metrics = await track.evaluate(e => {
      const root = e.closest('[data-news-ticker]'), section = root.closest('section');
      const h=e.querySelector('h3'), s=getComputedStyle(h), ts=getComputedStyle(e);
      const originals=[...e.querySelectorAll('[data-carousel-item]')];
      return {viewport:innerWidth,docWidth:document.documentElement.scrollWidth,sectionHeight:section.getBoundingClientRect().height,
        controls:root.querySelectorAll('button,input[type=range],[data-carousel-controls]').length,
        scrollbar:ts.scrollbarWidth,webkitScrollbar:getComputedStyle(e,'::-webkit-scrollbar').display,
        titleClamp:s.webkitLineClamp,titleHeight:h.getBoundingClientRect().height,titleLine:s.lineHeight,
        originalCount:originals.length,cloneCount:e.querySelectorAll('[data-news-clone]').length,playing:root.classList.contains('news-is-playing'),scroll:e.scrollLeft};
    });
    const start=await track.evaluate(e=>e.scrollLeft);
    await page.waitForTimeout(800);
    metrics.displacement=await track.evaluate((e,start)=>e.scrollLeft-start,start);
    report.viewports.push(metrics);
    await page.screenshot({path:`output/playwright/news-minimal-${width}.png`,fullPage:false});
  }
  await track.focus();
  const focusStart=await track.evaluate(e=>e.scrollLeft);
  await page.waitForTimeout(500);
  report.focusPause={start:focusStart,end:await track.evaluate(e=>e.scrollLeft)};
  report.loopSetup=await track.evaluate(e=>{
    const cycle=e.querySelector('[data-news-clone]').offsetLeft-e.querySelector('[data-carousel-item]').offsetLeft;
    e.scrollLeft=cycle-10;
    return {cycle,desired:cycle-10,actual:e.scrollLeft};
  });
  await page.waitForTimeout(100);
  await track.evaluate(e=>e.blur());
  report.boundary=await track.evaluate(async e=>{
    const values=[];
    for(let i=0;i<32;i++) {await new Promise(requestAnimationFrame); values.push(e.scrollLeft);}
    return values;
  });
  await page.emulateMedia({reducedMotion:'reduce'});
  await page.waitForTimeout(100);
  const reducedStart=await track.evaluate(e=>e.scrollLeft);
  await page.waitForTimeout(700);
  report.reducedMotion={start:reducedStart,end:await track.evaluate(e=>e.scrollLeft),playing:await track.evaluate(e=>e.closest('[data-news-ticker]').classList.contains('news-is-playing'))};
  return report;
}
