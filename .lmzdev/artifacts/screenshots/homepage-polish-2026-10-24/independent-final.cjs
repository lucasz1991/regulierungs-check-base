async (page) => {
  const out='.lmzdev/artifacts/screenshots/homepage-polish-2026-10-24';
  const dimensions=[];
  for(const width of [1237,1440,980,1024,768,390,320]) {
    await page.setViewportSize({width,height:1018});
    await page.goto('http://127.0.0.1:8186/');
    await page.evaluate(()=>document.fonts.ready);
    await page.waitForTimeout(300);
    const data=await page.evaluate(()=>{
      const rect=e=>{const r=e.getBoundingClientRect();return{x:+r.x.toFixed(1),w:+r.width.toFixed(1),h:+r.height.toFixed(1),right:+r.right.toFixed(1)}};
      const h=document.querySelector('h1'),lineMap={},walker=document.createTreeWalker(h,NodeFilter.SHOW_TEXT);let longestRight=0;
      while(walker.nextNode()){const node=walker.currentNode,re=/\S+/g;let m;while((m=re.exec(node.textContent))){const r=document.createRange();r.setStart(node,m.index);r.setEnd(node,m.index+m[0].length);const box=r.getBoundingClientRect();(lineMap[Math.round(box.top)]??=[]).push(m[0]);longestRight=Math.max(longestRight,box.right);}}
      return {width:innerWidth,documentWidth:document.documentElement.scrollWidth,bodyWidth:document.body.scrollWidth,heading:{...rect(h),font:getComputedStyle(h).fontSize,lines:Object.values(lineMap).map(v=>v.join(' ')),longestRight:+longestRight.toFixed(1)},process:[...document.querySelectorAll('#ablauf article')].map(e=>({article:rect(e),columns:getComputedStyle(e).gridTemplateColumns,content:[...e.children].map(c=>({tag:c.tagName,...rect(c),column:getComputedStyle(c).gridColumn})),description:[...e.querySelectorAll('p')].map(p=>({visible:getComputedStyle(p).display!=='none',...rect(p),font:getComputedStyle(p).fontSize}))})),about:{heading:getComputedStyle(document.querySelector('#plattform h2')).fontSize,body:getComputedStyle(document.querySelector('#plattform p')).fontSize},newsletter:{heading:getComputedStyle(document.querySelector('#newsletter h2')).fontSize,body:getComputedStyle(document.querySelector('#newsletter p')).fontSize}};
    });
    dimensions.push(data);
    await page.screenshot({path:`${out}/independent-hero-${width}.png`});
    if([768,390,320].includes(width))await page.locator('#ablauf').screenshot({path:`${out}/independent-process-${width}.png`});
    if(width===320){await page.locator('#plattform').screenshot({path:`${out}/independent-about-320.png`});await page.locator('#newsletter').screenshot({path:`${out}/independent-newsletter-320.png`});}
  }
  await page.setViewportSize({width:390,height:1018});
  await page.goto('http://127.0.0.1:8186/');
  await page.getByRole('button',{name:'Erfahrung teilen',exact:true}).click();
  await page.getByRole('button',{name:'Abbrechen',exact:true}).waitFor({state:'visible'});
  await page.waitForTimeout(350);
  const ratingFocus=await page.evaluate(()=>({tag:document.activeElement.tagName,label:document.activeElement.getAttribute('aria-label')}));
  await page.keyboard.press('Escape');
  await page.getByRole('button',{name:'Abbrechen',exact:true}).waitFor({state:'hidden'});
  const ratingReturn=await page.evaluate(()=>({tag:document.activeElement.tagName,text:document.activeElement.textContent.trim(),visible:document.activeElement.getBoundingClientRect().height>0}));
  await page.getByRole('button',{name:'Zum dunklen Modus wechseln',exact:true}).click();
  await page.waitForTimeout(500);
  const dark=await page.evaluate(()=>{
    function parse(c){const a=c.match(/[\d.]+/g)?.map(Number)||[];if(c.startsWith('color('))return [a[0]*255,a[1]*255,a[2]*255,a[3]??1];return [a[0],a[1],a[2],a[3]??1];}
    function lum(a){return a.slice(0,3).map(v=>{v/=255;return v<=.04045?v/12.92:((v+.055)/1.055)**2.4}).reduce((s,v,i)=>s+v*[.2126,.7152,.0722][i],0)}
    return ['#start h1','#plattform p','#newsletter p','footer a'].map(sel=>{const e=document.querySelector(sel),style=getComputedStyle(e);let parent=e,bg=[255,255,255,1];while(parent){let p=parse(getComputedStyle(parent).backgroundColor);if(p[3]===1){bg=p;break;}parent=parent.parentElement;}const fg=parse(style.color),a=lum(fg),b=lum(bg);return{selector:sel,font:style.fontSize,color:style.color,background:bg,contrast:+((Math.max(a,b)+.05)/(Math.min(a,b)+.05)).toFixed(2)}});
  });
  await page.evaluate(()=>window.scrollTo(0,0));
  await page.screenshot({path:`out-placeholder` .replace('out-placeholder',`${out}/independent-dark-390.png`)});
  await page.locator('#newsletter').screenshot({path:`${out}/independent-newsletter-dark-390.png`});
  await page.emulateMedia({reducedMotion:'reduce'});
  await page.goto('http://127.0.0.1:8186/');
  await page.getByRole('heading',{name:'News & Ratgeber',exact:true}).scrollIntoViewIfNeeded();
  const a=await page.locator('[data-news-ticker] [data-carousel-track]').evaluate(e=>e.scrollLeft);
  await page.waitForTimeout(850);
  const b=await page.locator('[data-news-ticker] [data-carousel-track]').evaluate(e=>e.scrollLeft);
  const reduced=await page.evaluate(()=>({toggle:document.querySelector('[data-news-toggle]').textContent,rootAnimations:[...document.querySelectorAll('#start [data-reveal],#start .hero-visual,#start .hero-copy,#ablauf [data-reveal]')].map(e=>({classes:e.className,animation:getComputedStyle(e).animationName,duration:getComputedStyle(e).animationDuration,transition:getComputedStyle(e).transitionDuration}))}));
  return {dimensions,ratingFocus,ratingReturn,dark,reduced:{...reduced,scrollBefore:a,scrollAfter:b},scripts:await page.locator('script[src]').evaluateAll(es=>es.map(e=>e.src).filter(s=>s.includes('/build/')))};
}
