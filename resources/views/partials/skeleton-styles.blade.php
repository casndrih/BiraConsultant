<style>
@keyframes shimmer {
  0% { background-position: -200px 0; }
  100% { background-position: calc(200px + 100%) 0; }
}
.skeleton-box {
  background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
  background-size: 200px 100%;
  animation: shimmer 1.5s infinite;
}
@media (prefers-color-scheme: dark) {
  .skeleton-box {
    background: linear-gradient(90deg, #1f2937 25%, #374151 50%, #1f2937 75%);
    background-size: 200px 100%;
  }
}
.dark .skeleton-box {
  background: linear-gradient(90deg, #1f2937 25%, #374151 50%, #1f2937 75%);
  background-size: 200px 100%;
}
</style>
