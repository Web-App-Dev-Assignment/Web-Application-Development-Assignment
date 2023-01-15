const canvas = document.querySelector('canvas');
const context = canvas.getContext('2d')
canvas.width= innerWidth
canvas.height = innerHeight
console.log(canvas)
console.log(context)

const PlayerScore = document.querySelector('#score');
const startGame = document.querySelector('#startGame')
class Player{
    constructor(x,y,radius,color,score){
        this.x = x
        this.y = y
        this.radius = radius
        this.color = color
        this.score = score
    }
    draw(){
        context.beginPath()
        context.arc(this.x,this.y,this.radius, 0, Math.PI*2,false)
        context.fillStyle=this.color
        context.fill()
    }
    update(){
        this.score++
    }
}

class Projectile{
    constructor(x,y,radius,color, velocity){
        this.x = x
        this.y = y
        this.radius = radius
        this.color = color
        this.velocity = velocity
    }
    draw(){
        context.beginPath()
        context.arc(this.x,this.y,this.radius, 0, Math.PI*2,false)
        context.fillStyle=this.color
        context.fill()
    }
    update(){
        this.draw()
        this.x = this.x + this.velocity.x
        this.y = this.y + this.velocity.y

    }
}
class Target{
    constructor(x,y,radius,color, velocity){
        this.x = x
        this.y = y
        this.radius = radius
        this.color = color
        this.velocity = velocity
    }
    draw(){
        context.beginPath()
        context.arc(this.x,this.y,this.radius, 0, Math.PI*2,false)
        context.fillStyle=this.color
        context.fill()
    }
    update(){
        this.draw()
        this.x = this.x + this.velocity.x
        this.y = this.y + this.velocity.y

    }
}
const x = canvas.width/2
const y = 500


let player = new Player(x,y,30,'blue',0)
let projectiles = []
let targets = []

function init(){
     player = new Player(x,y,30,'blue',0)
     projectiles = []
     targets = []
     
}
function getRandomInt(max) {
    return Math.floor(Math.random() * max)
  }
function spawnTargets(){
    
    setInterval(()=>{
        randomX = getRandomInt(3)
        if(randomX==0){
        randomX=1
        }
        const x=100
        const y = 100
        const radius = 30
        const color = 'green'
        const velocity = {x:randomX, y:0}
        targets.push(new Target(x,y,radius,color,velocity))
        //console.log(targets)
        //console.log(randomX)
    },3000)
}
//const projectile = new Projectile(canvas.width/2, canvas.height/2,5,'red',{x:1,y:1})
var count = 5000;
function counter(){
    count--
}
let animationId
function animate(){
    
    animationId=requestAnimationFrame(animate)
    context.clearRect(0,0,canvas.width,canvas.height)
    player.draw()
    projectiles.forEach((projectile) =>{projectile.update()})
    targets.forEach((target, index)=>{target.update()
    projectiles.forEach((projectile,projectileIndex) =>{const dist = Math.hypot(projectile.x - target.x,projectile.y-target.y)
            if(dist- target.radius - projectile.radius< 1){
                targets.splice(index, 1)
                projectiles.splice(projectileIndex,1)
                player.update()
                PlayerScore.innerHTML = player.score
                console.log(player.score)
            }
        })
    })
    counter()
    console.log(count)
    //projectile.draw()
    //projectile.update()
    if(count<0){
        window.alert('Times up, your Final scores : '+ player.score)
        cancelAnimationFrame(animationId)
        init()
        count=5000
        
    }
    
}

startGame.addEventListener('click',()=>{
    player.score=0
    PlayerScore.innerHTML = player.score
    spawnTargets()
    animate()
    setTimeout(()=>{addEventListener('click',(event)=>{
        const angle = Math.atan2(event.clientY-500,event.clientX-canvas.width/2)
        console.log(angle)
        const velocity = {x: Math.cos(angle), y: Math.sin(angle)}
        projectiles.push(new Projectile(canvas.width/2, 500,5,'red',velocity))})}, 3000);
})


console.log(player)


