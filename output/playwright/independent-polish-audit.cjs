async (page) => {
  const report = [];
  for (const width of [1237, 1440, 390, 320]) {
    await page.setViewportSize({ width, height: 1018 });
    await page.goto('http://127.0.0.1:8186/');
    await page.evaluate(() => document.fonts.ready);
    await page.waitForTimeout(350);
    const metrics = await page.evaluate(() => {
      const rect = el => { const r=el.getBoundingClientRect(); return {x:Math.round(r.x),y:Math.round(r.y+scrollY),w:Math.round(r.width),h:Math.round(r.height)}; };
      const h1=document.querySelector('h1');
      const words=[];
      const walk=document.createTreeWalker(h1,NodeFilter.SHOW_TEXT);
      while(walk.nextNode()) { const node=walk.currentNode; const re=/\S+/g; let m;
        while((m=re.exec(node.textContent))) { const range=document.createRange();range.setStart(node,m.index);range.setEnd(node,m.index+m[0].length);const r=range.getBoundingClientRect();words.push({word:m[0],top:Math.round(r.top)}); }
      }
      const lines={}; words.forEach(w=>(lines[w.top]??=[]).push(w.word));
      const sections=[...document.querySelectorAll('main section,footer')].map(e=>({id:e.id,class:e.className,...rect(e),font:getComputedStyle(e).fontSize}));
      const smaller=[...document.querySelectorAll('main button,main a,footer a')].filter(e=>{const r=e.getBoundingClientRect(),s=getComputedStyle(e);return r.width&&r.height&&s.visibility!=='hidden'&&r.width<innerWidth&&r.height<44;}).slice(0,25).map(e=>({text:(e.innerText||e.getAttribute('aria-label')||'').trim().slice(0,55),...rect(e)}));
      return {viewport:innerWidth,documentWidth:document.documentElement.scrollWidth,bodyWidth:document.body.scrollWidth,heroHeading:rect(h1),heroFont:getComputedStyle(h1).fontSize,heroLines:Object.values(lines).map(v=>v.join(' ')),sections,smaller};
    });
    await page.screenshot({path:`output/playwright/independent-polish-hero-${width}.png`});
    for (const selector of ['#ablauf','#plattform','#newsletter','footer']) {
      const section=page.locator(selector).first();
      if(await section.count()) {
        if(width===1237||width===390||width===320) await section.screenshot({path:`output/playwright/independent-polish-${selector.replace('#','')}-${width}.png`});
      }
    }
    report.push(metrics);
  }
  return report;
}
