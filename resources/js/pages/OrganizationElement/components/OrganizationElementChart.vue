<template>
  <svg width="325" height="178" ref="svg">
    <g class="chart">
      <g class="nodes"></g>
      <g class="links"></g>
    </g>
  </svg>
</template>

<script setup>
import { ref, computed } from '@vue/reactivity'
import { onMounted, nextTick } from '@vue/runtime-core'
import * as d3 from 'd3'
const props = defineProps({
  organization_element: {
    type: Object,
    required: true,
  },
})

const svg = ref('')

const width = 97 * props.organization_element.children.length + 50
const height = 178

const generateChart = () => {
  //chart deminsions and zoom handlin
  const chart = svg.value.querySelector('.chart')
  const nodes_group = svg.value.querySelector('.chart .nodes')
  const links_group = svg.value.querySelector('.chart .links')

  const zoom = d3.zoom().on('zoom', (e) => d3.select(chart).attr('transform', e.transform))
  d3.select(svg.value).attr('viewBox', `0 0 ${width} ${height}`).call(zoom)
  d3.select(chart).style('width', `${width}px`).style('height', `${height}px`)

  //chart data converting
  var treeLayout = d3.tree().size([width, height])
  var root = d3.hierarchy(props.organization_element)
  treeLayout(root)

  // Nodes
  const nodes = d3.select(nodes_group).selectAll('g').data(root.descendants()).enter().append('g')

  //for childrens
  const childrens = nodes.filter((d) => !d.data.hasOwnProperty('Type'))

  childrens
    .append('rect')
    .attr('x', function (d) {
      return d.x - 97 / 2
    })
    .attr('y', function (d) {
      return d.y - 30
    })
    .attr('width', function (d) {
      return '97'
    })
    .attr('height', function (d) {
      return '30'
    })
    .attr('fill', function (d) {
      return 'white'
    })
    .attr('stroke-width', function (d) {
      return '1'
    })
    .attr('stroke', function (d) {
      return '#E6DEE5'
    })
    .attr('rx', 4)

  childrens
    .append('text')
    .text(function (d) {
      return d.data.Name
    })
    .on('click', function (e, d) {
    //   console.log(d.data.id)
    })
    .attr('x', function (d) {
      return d.x
    })
    .attr('y', function (d) {
      return d.y - 30 + 30 / 2
    })
    .attr('text-anchor', 'middle')
    .attr('dominant-baseline', 'central')
    .attr('fill', '#13A3E5')
    .each(function () {
      var self = d3.select(this),
        textLength = self.node().getComputedTextLength(),
        text = self.text()
      while (textLength > 97 - 2 * 8 && text.length > 0) {
        text = text.slice(0, -1)
        self.text(text + '...')
        textLength = self.node().getComputedTextLength()
      }
    })
  //For Parent
  const parent = nodes.filter((d) => d.data.hasOwnProperty('Type'))
  parent
    .append('rect')
    .attr('x', function (d) {
      return d.x - 263 / 2
    })
    .attr('y', function (d) {
      return d.y
    })
    .attr('width', function (d) {
      return '268'
    })
    .attr('height', function (d) {
      return '101'
    })
    .attr('fill', function (d) {
      return '#AB326F'
    })
    .attr('rx', 4)

  for (const key in props.organization_element) {
    if (key == 'children') continue
    parent
      .append('text')
      .text(`${key} - `)
      .attr('x', function (d) {
        return d.x - 263 / 2 + 20
      })
      .attr('y', function (d) {
        if (key == 'Type') return 14
        if (key == 'Name') return 41
        if (key == 'Responsible') return 68
      })
      .attr('class', `${key}-key`)
      .attr('fill', 'white')
      .attr('dominant-baseline', 'text-before-edge')
      .style('font-weight', 'bold')

    parent
      .append('text')
      .text(props.organization_element[key])
      .attr('x', function (d) {
        const text_length = parent.select(`text.${key}-key`).node().getComputedTextLength()
        return d.x - 263 / 2 + text_length + 30
      })
      .attr('y', function (d) {
        if (key == 'Type') return 14
        if (key == 'Name') return 41
        if (key == 'Responsible') return 68
      })
      .attr('fill', 'white')
      .attr('dominant-baseline', 'text-before-edge')
  }

  //draw links from parent to childs

  d3.select(links_group)
    .selectAll('polyline.link')
    .data(root.links())
    .join('polyline')
    .classed('link', true)
    .attr('points', (d) => {
      const { source, target } = d
      return `${target.x},${target.y - 30} ${target.x},${target.y - 48} ${source.x},${target.y - 48} ${source.x},${source.y + 100}`
    })
    .attr('stroke-width', 1)
    .attr('stroke', '#636363')
    .attr('fill', 'none')
}

onMounted(() => {
  nextTick(generateChart)
})
</script>
